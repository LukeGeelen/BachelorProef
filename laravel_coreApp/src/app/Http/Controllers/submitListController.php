<?php

namespace App\Http\Controllers;

use App\Models\Check_result;
use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Check;
class submitListController extends Controller
{
    //
    public function viewPage(){
        $submissions = Submission::get()->sortByDesc('created_at');;
        return view('submissionList')->with("submissions",$submissions);
    }

    public function viewDetail($submissionId){
        $submission = Submission::find($submissionId);
        $results = Check_result::where('submission_id', $submissionId)->get();
        return view('submissionDetail')->with('submission', $submission)->with('results', $results);
    }
}
