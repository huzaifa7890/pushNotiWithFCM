<?php

namespace App\Jobs;

use App\Http\Controllers\NotificationController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NoficationJobSchedule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $title;
    protected $body;
    protected $key;

    public function __construct($title, $body, $key)
    {
        $this->title = $title;
        $this->body = $body;
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Executing NoficationJobSchedule');

        try {
            NotificationController::notify($this->title, $this->body, $this->key);
        } catch (\Exception $e) {
            \Log::error('Error in NoficationJobSchedule: ' . $e->getMessage());
        }
    }
}
