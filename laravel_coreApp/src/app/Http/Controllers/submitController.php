<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignments;
use App\Models\Submission;
use App\Models\Check;
use App\Models\Check_result;

class submitController extends Controller
{
    public function view($assignmentId){
        $assignment = Assignments::find($assignmentId);
        return view('assignmentDetail')->with('assignment',$assignment);
    }

    public function submit($assignmentId){

        $submission = new Submission;
        $submission->name = $_POST["name"];
        $submission->assignment_id = $assignmentId;
        $submission->save();
        mkdir('/var/www/storage/app/submissions/' . strval($submission->id), 0777, true);
        foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                // basename() may prevent filesystem traversal attacks;
                // further validation/sanitation of the filename may be appropriate
                $name = basename($_FILES["file"]["name"][$key]);
                move_uploaded_file($tmp_name, '/var/www/storage/app/submissions/' . strval($submission->id) .'/'. $name);
            }
        }
        //files stored for further reference

        //generate empty result units
        $checks = Check::where('assignment_id', $assignmentId)->get();

        $testData = array();
        foreach($checks as $check){
            $result = new Check_result;
            $result->submission_id = $submission->id;
            $result->check_id = $check->id;
            $result->save();

            $testData[] =
                [
                    'id' => $result->id,
                    'input'=>$check->input,
                    'output'=>$check->expected_output
                ];
        }

        $body = [
            'tests'=>$testData
        ];

        //generate check.json
        $checkjson = json_encode($body, JSON_UNESCAPED_LINE_TERMINATORS);


        $checksjsonPath = '/var/www/storage/app/submissions/' . strval($submission->id) . '/checks.json';
        file_put_contents($checksjsonPath, $checkjson);

        //send files
        $fileArray = glob('/var/www/storage/app/submissions/' . strval($submission->id) .'/*');
        $fileArray[] = $checksjsonPath;
        $postData = ['lang' => Assignments::find($assignmentId)->language];

        foreach ($fileArray as $index => $file){
            $postData['file[' . $index . ']'] = curl_file_create(
                realpath($file),
                mime_content_type($file),
                basename($file)
            );
        }

        $request = curl_init('http://185.206.146.202:80/startContainer.php');
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($request);

        die($result);

        curl_close();
        return redirect()->route('submissionDetail', ['submissionId' => $submission->id]);
    }

    public function report($testId, Request $request){
        $effective_output = $request->input('output');
        $passed = $request->input('passed');

        $result = Check_result::find($testId);
        $result->effective_output = $effective_output;
        $result->check_passed = $passed;
        $result->save();

        return "OK";

    }
}
