<?php
echo shell_exec('cd /home/wbb/darknet');
$out = shell_exec('ls -alrt');
echo "<pre>$out</pre>";
//$output = shell_exec('./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show < data/forTest1.txt > result_200930.txt');
//echo "<pre>$output</pre>";
?>
