<?php
echo 0;
$id = $_POST["id"];

// Execute darknet
$out = exec("cd /home/wbb/darknet;sudo ./darknet detect cfg/yolov3_box.cfg backup/yolov3_box_5000.weights "."/var/www/html/".$_POST["id"]."/".$_POST["id"].".jpg");
?>
