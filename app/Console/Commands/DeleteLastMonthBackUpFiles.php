<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteLastMonthBackUpFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:deleteBackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will delete the previous month back-up files';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $backupFiles = Storage::files('backup');
    
            $currentMonth = now()->format('F');
    
            foreach ($backupFiles as $backupFile) {
                $extractedDateArray = explode('_', basename($backupFile, '.sql'));
    
                // Ensure that the array has at least one element
                if ($extractedDateArray) {
                    // Select the first element of the array
                    $extractedDate = $extractedDateArray[0];
    
                    // Create a Carbon instance from the extracted date
                    $fileDate = Carbon::createFromFormat('F-j-Y', $extractedDate);
    
                    if ($fileDate->format('F') != $currentMonth) {
                        // Delete the file from storage
                        Storage::delete($backupFile);
    
                        // Log the successful deletion
                        Log::info('Deleted non-current file: ' . $backupFile);
                    }
                }
            }
    
            // Log successful run
            Log::info('Command db:deleteBackup executed successfully.');
        } catch (\Exception $e) {
            // Log any exceptions that occurred during execution
            Log::error('Error during execution of db:deleteBackup command: ' . $e->getMessage());
            
        }
        
    }
}
