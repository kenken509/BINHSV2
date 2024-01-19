<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DatabaseBackUpController extends Controller
{
    public function show()
    {
        $backupFiles = Storage::files('backup');

        return inertia('AdminDashboard/AdminPages/BackUp/BackUpAll',[
            'backupFiles' => $backupFiles,
        ]);
    }

    public function delete($filename)
    {
        $filePath = 'backup/' . $filename;

        
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);

            return redirect()->route('database.backup.show')->with('success', 'Deleted Successfully!');
            
        }

        return redirect()->route('database.backup.show')->with('error', 'Deletion Failed');
    }

    

    

    public function restore(Request $request)
    {
        $filename = $request->filename;
        $filePath = storage_path('app/public/backup/' . $filename);

        // Log the start of the restore process
        Log::info("Starting restore process for file: $filename");

        // Check if the backup file exists
        if (file_exists($filePath)) {
            // Log the existence of the backup file
            Log::info("Backup file found: $filePath");

            // Assuming you are dealing with a SQL backup file
            $sql = file_get_contents($filePath);

            // Assuming you have a database connection
            $dbHost = env('DB_HOST');
            $dbDatabase = env('DB_DATABASE');
            $dbUsername = env('DB_USERNAME');
            $dbPassword = env('DB_PASSWORD');

            $command = "mysql -h $dbHost -u $dbUsername -p$dbPassword $dbDatabase < $filePath 2>&1"; // Redirect STDERR to STDOUT

            // Log the command being executed
            Log::info("Executing command: $command");

            // Execute the restore command
            exec($command, $output, $returnValue);

            // Log the output and return value of the command
            Log::info("Command output: " . implode("\n", $output));
            Log::info("Command return value: $returnValue");

            if ($returnValue === 0) {
                // Restore successful
                Log::info("Backup restored successfully");
                return redirect()->route('database.backup.show')->with('success', 'Database restored successfully!');
            } else {
                // Restore failed
                Log::error("Failed to restore backup. Command output: " . implode("\n", $output));
                Log::info("Command return value: $returnValue");
                return redirect()->route('database.backup.show')->with('error', 'Database restoration failed!');
            }
        } else {
            // Backup file not found
            Log::error("Command output from else: " . implode("\n", $output));
            Log::error("Backup file not found: $filePath");
            return redirect()->route('database.backup.show')->with('error', 'Backup file not found!');
        }
    }




}
