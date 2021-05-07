<?php
$type = $_GET["type"];
if($type == "result"){
    $id = $_GET["id"];
    $output = $_GET['output'];
    $passed = $_GET['passing']=="True";
    $data = [
        "output"=>$output,
        "passed"=>$passed];
    $url = 'http://185.206.146.202:81/api/reportResult/' . $id;
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );

    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    die("OK");
    if ($result === FALSE) { die("FAILED"); }
}elseif($type == "compiler"){
    $submissionId = $_GET['id'];
    $compilerOut = $_GET['compilerOutput'];
    $data = [
       "compilerOutput"=>$compilerOut];
    $url = 'http://185.206.146.202:81/api/reportCompiler/' . $submissionId;
    $options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    die("OK");

}elseif($type =="linter"){
    $submissionId = $_GET['id'];
    $linterOut = $_GET['linterOutput'];
    $data = [
       "linterOutput"=>$linterOut];
    $url = 'http://185.206.146.202:81/api/reportLinter/' . $submissionId;
    $options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    die("OK");

}else{
    http_response_code (400);
    die("mode not supported");
}
?>
