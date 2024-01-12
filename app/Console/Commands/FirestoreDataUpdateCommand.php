<?php
// App\Console\Commands\FirestoreDataUpdateCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\FirestoreDataUpdateJob;

class FirestoreDataUpdateCommand extends Command
{
    protected $signature = 'firestore:update';
    protected $description = 'Update Firestore data';

    public function handle()
    {
        // Dispatch the job
        dispatch(new FirestoreDataUpdateJob);

        $this->info('Firestore data update job dispatched successfully.');
    }
}
