
<?php
echo "hello, result processor reached";
$inputJSON = file_get_contents('php://input');
echo $inputJSON;
$data = json_decode($inputJSON, TRUE);

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
print $result;
if ($result === FALSE) { die("FAILED"); }
?>
