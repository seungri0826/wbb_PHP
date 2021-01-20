<?php
$output;
$return_var;
$para1 = "detector";
$para2 = "test";
$para3 = "cfg/obj.data";
$para4 = "cfg/yolov3_box.cfg";
$para5 = "backup/yolov3_box_5000.weights";
$para6 = "-dont_show";
$para7 = "< data/forTest.txt";
$para8 = "> result_200926.txt";

exec("cd ~/darknet");
exec("./darknet '".$para1."' '".$para2."' '".$para3."' '".$para4."' '".$para5."' '".$para6."'  <data/forTest1.txt >result_200927.txt", $output, $return_var );
echo '$output : ';
print_r($output);
echo '<br>';
?>
