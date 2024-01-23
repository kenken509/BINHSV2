<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ThreeDFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class ThreeDFileController extends Controller
{

    // Instructor part controller ****************************************************************
    
    public function approved3dShowAll()
    {
        $instructorId = Auth::user()->id;

        $approved3D = ThreeDFile::where('status', 'approved')
                                ->where('created_by', $instructorId)
                                ->with('instructor', 'instructor.subject')
                                ->with('approver')
                                ->orderBy('approved_date','desc')->paginate(10);

        
        return inertia('AdminDashboard/AdminPages/3d/Instructor/Approved3d',[
            'approved3D' => $approved3D,
        ]);
    }
    
    public function pending3dShowAll()
    {
        $instructorId = Auth::user()->id;
        $pending3d = ThreeDFile::where('status', 'pending')
            ->where('created_by', $instructorId)
            ->with('instructor') // Eager load the 'instructor' relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return inertia('AdminDashboard/AdminPages/3d/Instructor/Pending3dAll', [
            'pending3d' => $pending3d,
        ]);
    }

    public function rejected3dShowAll()
    {
        $instructorId = Auth::user()->id;
        $rejected3d = ThreeDFile::where('status', 'rejected')
            ->where('created_by', $instructorId)
            ->with('instructor') // Eager load the 'instructor' relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return inertia('AdminDashboard/AdminPages/3d/Instructor/Rejected3dAll', [
            'rejected3d' => $rejected3d,
        ]);
    }

    public function rejected3dDelete($id)
    {
        $file = ThreeDFile::findOrFail($id);
        if($file->image){
            Storage::disk('public')->delete($file->image);
        }
            
        $file->delete();
        
        return redirect()->route('3d.rejected3dShowAll')->with('success', 'Deleted Successfully!');
    }
    
    public function pending3dShowAdd()
    {
        return inertia('AdminDashboard/AdminPages/3d/Instructor/Add3d');
    }

    public function pending3dStore(Request $request)
    {
        try {
            DB::beginTransaction();

            $instructor = Auth::user();

            $imageFile = $request->file('image');
            $originalName = $imageFile->getClientOriginalName();
            $randomString = Str::random(10);
            $imageNewName = $randomString . '_' . $originalName;

            $path = $imageFile->storeAs('Images', $imageNewName, 'public');

            $new3d = new ThreeDFile();

            $new3d->title = $request->title;
            $new3d->description = $request->description;
            $new3d->image = $path;
            $new3d->threeDLink = $request->threeDLink;
            $new3d->subject_id = $instructor->subject_id;
            $new3d->status = 'pending';
            $new3d->created_by = $instructor->id;
            $new3d->save();

            DB::commit();

            return redirect()->route('3d.pending3dShowAll')->with('success', 'Successfully Added new 3D.');
        } catch (\Exception $e) {
            DB::rollback();
            // Log or handle the exception as needed
            Log::info("this is the error: $e");
            return back()->withErrors(['error' => 'Error occurred while adding new 3D. Please try again.']);
        }
    }

    public function pending3dDelete($id)
    {
       
        $file = ThreeDFile::findOrFail($id);
        if($file->image){
            Storage::disk('public')->delete($file->image);
        }
            
        $file->delete();
        
        return redirect()->route('3d.pending3dShowAll')->with('success', 'Deleted Successfully!');
    }
    

    public function pending3dEdit($id)
    {
        
        $fileToEdit = ThreeDFile::findOrFail($id);

        return inertia('AdminDashboard/AdminPages/3d/Instructor/Edit3d',[
            'fileToEdit' => $fileToEdit,
        ]);
    }

    public function pending3dUpdate(Request $request)
    {
        
        $fileToUpdate = ThreeDFile::findOrFail($request->id);
        

        // Check if there is a new image file in the request
        if ($request->hasFile('image')) {
            try{
                DB::beginTransaction();
                // Delete the old image file
                Storage::delete($fileToUpdate->image);
                

                $imageFile = $request->file('image');
                $originalName = $imageFile->getClientOriginalName();
                $randomString = Str::random(10);
                $imageNewName = $randomString . '_' . $originalName;

                $path = $imageFile->storeAs('Images', $imageNewName, 'public');


                $fileToUpdate->title = $request->title;
                $fileToUpdate->description = $request->description;
                $fileToUpdate->image = $path;
                $fileToUpdate->threeDLink = $request->threeDLink;
                $fileToUpdate->save();
                DB::commit();

                return to_route('3d.pending3dShowAll')->with('success', 'Updated Successfully.');
                
            }
            catch (QueryException $e) 
            {
                DB::rollback();
                // Log the database exception
                Log::error("Database Exception: {$e->getMessage()}");
    
                // Return a response or redirect with an error message
                return redirect()->route('3d.pending3dShowAll')->with('error', 'Database error occurred!');
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                // Log or handle the exception as needed
                Log::info("this is the error: $e");
                return to_route('3d.pending3dShowAll')->with('error', 'Update Failed!');
            }
        }
        else
        {
            try
            {
                DB::beginTransaction();

                $fileToUpdate->title = $request->title;
                $fileToUpdate->description = $request->description;
                $fileToUpdate->threeDLink = $request->threeDLink;
                $fileToUpdate->save();

                DB::commit();

                return to_route('3d.pending3dShowAll')->with('success', 'Updated Successfully.');
            }
            catch (QueryException $e) 
            {
                DB::rollback();
                // Log the database exception
                Log::error("Database Exception: {$e->getMessage()}");
    
                // Return a response or redirect with an error message
                return redirect()->route('3d.pending3dShowAll')->with('error', 'Database error occurred!');
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                // Log or handle the exception as needed
                Log::info("this is the error: $e");
                return to_route('3d.pending3dShowAll')->with('error', 'Update Failed!');
            }
                
        }

        
        
    }

    // Instructor part controller ****************************************************************



    // Admin part controller ****************************************************************
    public function approved3dShow()
    {
        $approved3D = ThreeDFile::where('status','approved')
                            ->with('instructor')
                            ->orderBy('created_at','desc')->paginate(10);

        $searchApproved3D = ThreeDFile::where('status','approved')
                            ->with('instructor', 'instructor.subject')
                            ->orderBy('created_at','desc')->get();
                    

        
        return inertia('AdminDashboard/AdminPages/3d/Admin/Approved',[
            'approved3D' => $approved3D,
            'searchApproved3D' => $searchApproved3D,
        ]);                   
    }
    
    public function pending3dShow()
    {
    
        $pending3d = ThreeDFile::where('status','pending')
                            ->with('instructor')
                            ->orderBy('created_at','desc')->paginate(10);

        return inertia('AdminDashboard/AdminPages/3d/Admin/Pending3d',[
            'pending3d' => $pending3d,
        ]);
    }

    public function approve3d($id)
    {
        $file = ThreeDFile::findOrFail($id);
        $instructorId = Auth::user()->id;

        try{

            DB::beginTransaction();
            $file->status = 'approved';
            $file->approved_by  = $instructorId;
            $file->approved_date = now();
            $file->save();

            DB::commit();
            return redirect()->route('3d.approved3dShow')->with('success', 'Approved Successfully.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            // Log or handle the exception as needed
            Log::info("this is the error: $e");
            return redirect()->back->with('error', 'Approval Failed!');
        }
        

    } 
   
    

    public function reject3d($id)
    {
        $file = ThreeDFile::findOrFail($id);
        $instructorId = Auth::user()->id;

        try{

            DB::beginTransaction();
            $file->status = 'rejected';
            $file->approved_by  = $instructorId;
            $file->approved_date = now();
            $file->save();

            DB::commit();
            return to_route('3d.pending3dShow')->with('success', 'Rejected Successfully.');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            // Log or handle the exception as needed
            Log::info("this is the error: $e");
            return redirect()->back->with('error', 'Rejection Failed!');
        }
    }
    // Admin part controller ****************************************************************
}
