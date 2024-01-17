<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use App\Models\User;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function show(){
        
        
        
        return inertia('AdminDashboard/AdminPages/SectionManagement/SectionAll', [
            'sections' => Section::with(['subject', 'instructors', 'student' => function ($query){
                $query->with('section');
            } ])->latest()->get(),
            'studentUser' => User::all(),
        ]);
    }
    
    public function create(){
       
        return inertia('AdminDashboard/AdminPages/SectionManagement/SectionAdd', [
            'subjects' => Subject::with('instructor')->get(),
        ]);
    }

    public function store(Request $request){
        
        
        
        //{"name":"asdf","subject_id":2,"instructor_id":3,"maxStudents":20}
       // Validate the request data
        $validatedData = $request->validate([
            'name'          => 'required|unique:sections',
            'subject_id'    => 'required',
            'instructor_id' => 'required',
            'maxStudents'   => 'required|numeric|min:10',
        ], [
            'name.unique' => 'Please enter a non-existing section.',
            'maxStudents.min'  => 'The number of minimum students should be at least 10.',
        ]);
        
        try {
            // Start a database transaction
            DB::beginTransaction();
            // Create a new Section instance
            $newSection = new Section();
            $newSection->name = $validatedData['name'];
            $newSection->subject_id = $validatedData['subject_id'];
            $newSection->maxStudents = $validatedData['maxStudents'];
            $newSection->created_at = Carbon::now();
            $newSection->save();

            // Attach instructor to the section
            $newSection->instructors()->attach($validatedData['instructor_id']);

            // Commit the transaction if all operations are successful
            DB::commit();

            return redirect()->route('section.show')->with('success', 'Successfully added new section!');
        } catch (\Exception $e) {

            // Rollback the transaction if an exception occurs
            DB::rollBack();

            // Handle the exception, you might want to log or display an error message
            return $e;
        }
        
    }

    public function delete(Section $section){
        $section->instructors()->detach();
        $section->delete();
        return redirect()->back()->with('success', 'Successfully deleted!');
    }

    public function edit($id){
       
        return inertia('AdminDashboard/AdminPages/SectionManagement/SectionEdit', [
            'section' => Section::with(['subject','instructors'])->findOrFail($id),
            'subjects' => Subject::with('instructor')->get(),
        ]);
    }

    public function update(Request $request){

        
        // {"section_id":6,"name":"32A1","subject_id":2}
        $data = $request->validate([
            'name'          => 'required',
            'subject_id'    => 'required',
            'instructor_id' => 'required',
            'maxStudents'   => 'required|numeric|min:10',
        ],[
            'maxStudents.min'  => 'The number of minimum students should be at least 10.',
        ]);

        if($data){
            try{
                // Start a database transaction
                DB::beginTransaction();

                $section = Section::findOrFail($request->section_id);

                $section->name          = $request->name;
                $section->subject_id    = $request->subject_id;
                $section->maxStudents    = $request->maxStudents;
                $section->updated_by    = Auth::user()->id;
                $section->updated_at    = Carbon::now();
                $section->save();
                // update the pivot table with sync function
                $section->instructors()->sync([$request->instructor_id]);

                // save database transaction
                DB::commit();

                return redirect()->route('section.show')->with('success', 'Successfully Updated');
            } catch (Exception $e) {
                 // Rollback the transaction if an exception occurs
                DB::rollBack();

                // Handle the exception, you might want to log or display an error message
                return $e;
            }
        }

        
    }
}
