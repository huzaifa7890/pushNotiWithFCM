<?php

namespace App\Http\Controllers;

use App\Jobs\NoficationJobSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public static function notify($title, $body, $device_key)
    {
        \Log::info('Sending notification to ' . $device_key);
        $url = "https://fcm.googleapis.com/fcm/send";
        $server_key = env('serverkey', 'sync');

        $dataArr = [
            "click_action" => "FLUTTER_NOTIFICATION_CLICK",
            "status" => "done",
        ];
        $data = [
            "registration_ids" => [($device_key)], 
            "notification" => [
                "title" => $title,
                "body" => $body,
                "sound" => "default",
            ],
            "data" => $dataArr,
            "priority" => "high",
        ];
        $encodeddata = json_encode($data);

        $header = [
            "Authorization:key=" . $server_key,
            "Content-Type:application/json",
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        //DISABL SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeddata);

        //exceute post
        $result = curl_exec($ch);
        \Log::info('FCM API Response: ' . $result);
        if ($result == false) {
            return [
                'message' => 'fialed',
                'r' => $result,
                'success' => false,
            ];
        }

        curl_close($ch);
        \Log::info('Notification sent successfully.');
        return [
            'message' => 'success',
            'r' => $result,
            'success' => true,
        ];

    }
    public function testqueues(Request $request)
    {
        $users = User::whereNotNull('device_key')->whereNotNull('isSubscribe')->get();

        foreach ($users as $user) {
            dispatch(new NoficationJobSchedule($user->name, $user->email, $user->device_key))->delay(now()->addMinutes());
        }

    }
    public function notifyapp(Request $request){
        return $this->notify($request->title,$request->body,$request->key);
    }

}
