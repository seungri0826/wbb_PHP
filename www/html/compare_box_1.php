<?php
/*
// id를 얻기 위해서는 darknet exec하는 코드에서도 Rpi - Jetson 연결 필요할 듯
// Rpi - Jetson image transmission
$file = $_FILES['userfile'];
if (!$file) {
    http_response_code(700);
    return;
}
if (!is_dir("/".$_POST["id"])) {
    mkdir("./".$_POST["id"]);
}
move_uploaded_file($file["tmp_name"], "./".$_POST["id"]."/".$_POST["id"].".jpg");

$id = $_POST["id"];
$uid = $id;

// Execute darknet
$out = exec("cd /home/wbb/darknet;sudo ./darknet detect cfg/yolov3_box.cfg backup/yolov3_box_5000.weights "."/var/www/html/".$_POST["id"]."/".$_POST["id"].".jpg");
*/

echo 0;

$id = $_POST["id"];

// Execute darknet
$out = exec("cd /home/wbb/darknet;sudo ./darknet detect cfg/yolov3_box.cfg backup/yolov3_box_5000.weights "."/var/www/html/".$_POST["id"]."/".$_POST["id"].".jpg");

?>
