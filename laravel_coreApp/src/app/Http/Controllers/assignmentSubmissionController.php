<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\Assignments;
use App\Models\MOSS;
class assignmentSubmissionController extends Controller
{
    public function view($assignmentId){
        $submissions = Submission::where('assignment_id', $assignmentId)->get();
        $assignment = Assignments::find($assignmentId);
        return view('assignmentSubmission')->with('submissions', $submissions)->with('assignment', $assignment);
    }


    public function controleerOpPlagiaat($assignmentId){
        $submissions = Submission::where('assignment_id', $assignmentId)->get();
        $assignment = Assignments::find($assignmentId);
        $userid = "853544121";
        $moss = new MOSS($userid);
        $moss->setLanguage(strtolower($assignment->language));




        foreach($submissions as $submission){
            //add files

            $moss->addByWildcard($submission->resource_path .'/*.c' );
        }
        $moss->setCommentString("This is a test");
        $url = $moss->send();
        header("Location: " . $url);
    }
}
