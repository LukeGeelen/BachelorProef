<?php
//parse_str(implode('&', array_slice($argv, 1)), $_GET);
if($_POST["lang"] != "C"){
	die("not supported language");
}
 foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                // basename() may prevent filesystem traversal attacks;
                // further validation/sanitation of the filename may be appropriate
                $name = basename($_FILES["file"]["name"][$key]);
                move_uploaded_file($tmp_name, '/var/www/html/docker/code' . '/'. $name);
            }
}
echo "starting";
$build = shell_exec("docker build -t student_execution /var/www/html/docker/ --build-arg CACHEBUST=$(date +%s)");

echo "build done, running";
$run =  shell_exec("docker run --net host -t student_execution" .  "> /dev/null 2>/dev/null &");
echo "done";

$files = glob('/var/www/html/docker/code/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file)) {
    unlink($file); // delete file
  }
}
echo "finished cleanup";
?>
