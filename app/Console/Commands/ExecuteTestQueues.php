<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\NoficationJobSchedule;
use App\Models\User;

class ExecuteTestQueues extends Command
{
    protected $signature = 'execute:testqueues';
    protected $description = 'Execute the testqueues method in NotificationController';

    public function handle()
    {
        $users = User::whereNotNull('device_key')->whereNotNull('isSubscribe')->get();

        foreach ($users as $user) {
            dispatch(new NoficationJobSchedule($user->name, $user->email, $user->device_key));
        }

        $this->info('Notification jobs dispatched successfully.');

        return Command::SUCCESS;
    }
}
