<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Process;

class SyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Replace 'path/to/your/master.bat' with the actual path to your batch file
        $batchFilePath = 'E:\HaefaDB\Local-Server-Include.bat';

        // Create a new Process instance and run the batch file
        $process = new Process(['cmd', '/c', $batchFilePath]);
        
        $process->run();

        // Check if the process was successful
         if ($process->isSuccessful()) {
        // Batch file executed successfully
        $output = $process->getOutput();
        // You can log or perform any necessary actions with the $output here
        } else {
            // Error occurred during batch file execution
            $output = $process->getErrorOutput();
            // You can log or handle the error here
        }
    }
}
