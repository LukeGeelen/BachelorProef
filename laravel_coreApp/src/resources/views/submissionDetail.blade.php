@php
    use App\Models\Assignments;
    use App\Models\Check;
    use App\Models\Check_result;
    $assignment = Assignments::find($submission->assignment_id);
@endphp

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Noname ticketsysteem</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Raleway:wght@100;200;300;306;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ URL::asset('style.css')}}">
</head>
<body>
<!-- titlebar -->
<div class="containter">
    <div class='row' style="margin-top:20px">
        <div class='col-md-2'>
        </div>
        <div class='col-md-8'>
            <h1>SUBMISSIONS</h1>
        </div>
        <div class='col-md-2'>
        </div>
    </div>
</div>


<!-- content -->
<div class="containter">
    <div class='row' style="margin-top:20px">
        <div class='col-md-2'>
        </div>
        <div class='col-md-8'>
            <div class="kader">
                <div class='assignmentTitle'>
                    {{$assignment->title . ' By ' . $submission->name }}
                </div>
                <div class='assignmentDiscription'>
                    {{$submission->created_at}}
                </div>
                <div class='assignmentDiscription'>
                    {{$assignment->description}}
                </div>
                <br>


                <div class='assignmentDiscription'>
                    <b>Results:</b>
                </div>
                <br>
                @foreach($results as $result)
                <!-- check block -->

                    @if($result->check_passed == true)
                        <div class='checkKaderPassed'>
                    @elseif(is_null($result->check_passed) == false)
                        <div class="checkKaderFailed">
                    @else
                        <div class="checkKaderPending">
                    @endif
                    <b>Check {{ $loop->index +1}}:</b>
                    <br>

                    Input: "{{Check::find($result->check_id)->input}}" <br>
                    expected output: "{{Check::find($result->check_id)->expected_output}}" <br>
                    actual output: @if(is_null($result->check_passed) == false)"{{$result->effective_output}}"
                            @else  ... pending, please wait ... @endif <br>
                </div>
                    <br>
                @endforeach


            </div>
        </div>
        <div class='col-md-2'>
        </div>
    </div></div>


</body></html>
