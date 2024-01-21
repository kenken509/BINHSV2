<?php

namespace App\Http\Controllers;

use App\Models\ThreeDFile;
use Illuminate\Http\Request;

class ThreeDFileController extends Controller
{
    public function pending3dShow()
    {
        $pending3d = ThreeDFile::where('status','pending')->orderBy('created_at','desc')->paginate(2);

        return inertia('AdminDashboard/AdminPages/3d/Admin/Pending3d',[
            'pending3d' => $pending3d,
        ]);
    }

    public function pending3dShowAdd()
    {
        return inertia('AdminDashboard/AdminPages/3d/Instructor/Add3d');
    }

    public function pending3dStore(Request $request)
    {
        dd($request);
        $new3d = new ThreeDFile();

        $new3d->title = $request->title;
        $new3d->description = $request->description;

        
    }
    
}
