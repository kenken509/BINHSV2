<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\ThreeDFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ThreeDFileController extends Controller
{
    public function pending3dShow()
    {
    
        $pending3d = ThreeDFile::where('status','pending')->orderBy('created_at','desc')->paginate(2);

        return inertia('AdminDashboard/AdminPages/3d/Admin/Pending3d',[
            'pending3d' => $pending3d,
        ]);
    }

    //instructor pending page
    public function pending3dShowAll()
    {
        $instructorId = Auth::user()->id;
        $pending3d = ThreeDFile::where('status', 'pending')
            ->where('created_by', $instructorId)
            ->with('instructor') // Eager load the 'instructor' relationship
            ->orderBy('created_at', 'desc')
            ->paginate(2);

        return inertia('AdminDashboard/AdminPages/3d/Instructor/Pending3dAll', [
            'pending3d' => $pending3d,
        ]);
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
        } catch (Exception $e) {
            DB::rollback();
            // Log or handle the exception as needed
            Log::info("this is the error: $e");
            return back()->withErrors(['error' => 'Error occurred while adding new 3D. Please try again.']);
        }
    }
    
}
