<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExecuteMasterBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:master-batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Databases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
        $desktopPath = 'E:\\HaefaDB'; // Update with your desktop path
        $batchFileName = 'Local-Server-Include.bat';

        $batchFilePath = "{$desktopPath}\\{$batchFileName}";
        
        // dd($batchFilePath);

       
        exec("start /B $batchFilePath", $output, $returnCode);
     

        if ($returnCode !== 0) {
            $this->error("Batch file execution failed with exit code: $returnCode");
        } else {
            // $this->info('Batch file executed successfully.');
        $this->error("Batch file execution failed with exit code: $returnCode");
        }
    }
}
