<?php
//$contents = file_get_contents('/home/wbb/shell/test.sh');
//echo shell_exec($contents);
//echo "<pre>$contents</pre>";

//$output = shell_exec('/home/wbb/shell/test3.sh');
//echo "<pre>$output</pre>";

//$out = shell_exec("/home/wbb/shell/test4.sh"); // 아니면 이거인듯
//echo "<pre>$out</pre>";

$out = null;
//exec("cd /home/wbb/darknet; ./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show < data/forTest.txt > result_200930.txt 2>&1", $out);
///exec("cd /home/wbb/darknet;./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights data/ex/1.png 2>&1", $out); /// 아마 이거하고 접근시각 바뀐듯
///exec("cd /home/wbb/darknet;./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show < data/forTest1.txt > result_201005_1.txt 2>&1", $out);
exec("cd /home/wbb/darknet;./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show data/ex/1.png > result_201005_2.txt", $out);
//exec("cd /home/wbb/darknet;ls -alrt 2>&1",$out);
//////exec("/home/wbb/darknet detector test /home/wbb/darknet/cfg/obj.data /home/wbb/darknet/cfg/yolov3_box.cfg /home/wbb/darknet/backup/yolov3_box_5000.weights -dont_show < /home/wbb/darknet/data/forTest.txt > /home/wbb/darknet/result_200930.txt", $out);
////exec("/home/wbb/darknet detector test /home/wbb/darknet/cfg/obj.data /home/wbb/darknet/cfg/yolov3_box.cfg /home/wbb/darknet/backup/yolov3_box_5000.weights data/ex/1.png 2>&1", $out);
var_dump($out);

//exec("cd /home/wbb/darknet;./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights data/ex/1.png", $out);
//var_dump($out);
?>
