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

    
}
