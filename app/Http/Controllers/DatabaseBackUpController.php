<?php

namespace App\Http\Controllers;

use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

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

         
         // Check if the file exists
        if (file_exists($filePath)) {
            // Read the SQL file content
            $sql = file_get_contents($filePath);

            // Wrap the restoration in a try-catch block
            try {
                // Disable foreign key checks to prevent issues during the import
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Run the SQL to restore the database
                DB::unprepared($sql);

                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                return redirect()->route('database.backup.show')->with('success', 'Database restored successfully!');
            } catch (Exception $e) {
                return redirect()->route('database.backup.show')->with('error', 'Database restoration failed!');
            }
        } else {
            return redirect()->route('database.backup.show')->with('error', 'Backup file not found.');
        }
        
    }

}
