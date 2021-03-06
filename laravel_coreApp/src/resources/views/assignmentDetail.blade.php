<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BP</title>
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
        <div class='col-8'>
            <h1>SUMBIT YOUR ASSIGNMENT</h1>
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
        <div class='col-8'>
            <div class="kader">
                <div class='assignmentTitle'>
                    {{$assignment->title}}
                </div>
                <div class='assignmentDiscription'>
                    {{$assignment->description}}
                </div>
                <br>
                <div class='assignmentDiscription'>
                    please upload all project files (including makefile)
                    make sure the makefile generates the program as main.out

                </div>
                <br>
                <form enctype="multipart/form-data"  method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <label for="name">name:</label><br>
                    <input type="text" id="name" name="name"><br>

                    <label for="files"></label><br>
                    <input type="file" name="file[]" id="file[]" multiple>

                    <input class='assignmentButton 'type="submit" value="SUBMIT">
                </form>

            </div>
        </div>
        <div class='col-md-2'>
        </div>
    </div></div>

</body>
