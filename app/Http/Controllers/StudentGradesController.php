<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Section;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use App\Models\StudentActiveQuiz;


class StudentGradesController extends Controller
{
    

    public function show()
    {
        
        $instructor = User::where('id', Auth::user()->id)->first();
        
        $handledSections = User::where('id',Auth::user()->id)->with('instructorSections')->first();
           
        $handledSectionIds = $handledSections->instructorSections->pluck('id')->toArray();
       
        $studentGrades = StudentGrade::with('student','student.section')->whereHas('student', function ($query) use ($handledSectionIds) {
            $query->where('role', '=', 'student')
                ->whereHas('section', function ($query) use ($handledSectionIds) {
                    $query->whereIn('id', $handledSectionIds);
                })
                ->with(['studentActiveQuiz' => function ($query) {
                    $query->whereIn('status', ['passed', 'failed'])
                        ->with(['quiz' => function ($query) {
                            $query->with('question');
                        }])->with('section');
                }]);
        })->get();
            
        
        $studentQuizInfo = StudentActiveQuiz::with(['quiz' => function ($query){
            $query->with('question');
        }])->where('status', ['passed','failed','lapse'])->get();
        
        
        return inertia('AdminDashboard/AdminPages/StudentGrades/InstructorPage/StudentGradesAll',[
            'studentGrades' => $studentGrades,
            'studentQuizInfo' => $studentQuizInfo,
        ]);
        
    }
}
