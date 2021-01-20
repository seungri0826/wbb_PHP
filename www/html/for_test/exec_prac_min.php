<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
//echo system('sudo whoami');

$inputFile = "data/forTest2.txt";
$outputFile = "result_201008_2.txt";

//exec("cd /home/wbb/darknet;sudo ./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show data/ex/1.png > result_201007_2.txt 2>&1", $out);

//exec("cd /home/wbb/darknet;sudo ./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show data/ex/1.png > result_201007_3.txt 2>&1", $out);

exec("cd /home/wbb/darknet;sudo ./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show data/ex/ex1.jpg > result_201008_2.txt  2>&1", $out);
var_dump($out);
?>
