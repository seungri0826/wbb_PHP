<?php
$fname = "test_201008_4.txt";
$out = shell_exec("cd /home/wbb/darknet;./darknet detector test cfg/obj.data cfg/yolov3_box.cfg backup/yolov3_box_5000.weights -dont_show data/ex/1.png");
//var_dump($out);
//echo "<pre>$out</pre>";
if ($fp = fopen($fname, "w")) {
    fwrite($fp, $out);
    fclose($fp);
}
?>
