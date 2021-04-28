<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignments;
class indexController extends Controller
{
    public function viewIndex(){
        //fetch assignments
        $assignments = Assignments::get();
        return view('assignmentList')->with('assignments', $assignments);
    }
}
