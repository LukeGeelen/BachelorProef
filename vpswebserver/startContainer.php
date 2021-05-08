<?php
//parse_str(implode('&', array_slice($argv, 1)), $_GET);
if($_POST["lang"] == "C"){
//	die("not supported language");

foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                // basename() may prevent filesystem traversal attacks;
                // further validation/sanitation of the filename may be appropriate
                $name = basename($_FILES["file"]["name"][$key]);
                move_uploaded_file($tmp_name, '/var/www/html/dockerC/code' . '/'. $name);
            }
}
//echo "starting";
$build = shell_exec("docker build -t student_execution /var/www/html/dockerC/ --build-arg CACHEBUST=$(date +%s)"); //we run build synchronous so it gets the time to copy the source files

$run =  shell_exec("docker run -v CLog:/log -t student_execution > /dev/null 2>/dev/null &");
//echo $run;
//echo "done";

$files = glob('/var/www/html/dockerC/code/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}
die("OK");
//echo "finished cleanup";
}elseif($_POST["lang"] == "java"){
foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                // basename() may prevent filesystem traversal attacks;
                // further validation/sanitation of the filename may be appropriate
                $name = basename($_FILES["file"]["name"][$key]);
                move_uploaded_file($tmp_name, '/var/www/html/dockerJava/code' . '/'. $name);
            }
}


$build = shell_exec("docker build -t student_execution /var/www/html/dockerJava/ --build-arg CACHEBUST=$(date +%s)");


$run =  shell_exec("docker run -v JavaLog:/log -t student_execution > /dev/null 2>/dev/null &");


$files = glob('/var/www/html/dockerJava/code/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}
die("OK");  
}else{
    die("language not supported");
}

?>
