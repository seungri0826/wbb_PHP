<?php
$filename = "/home/wbb/darknet/result_201017.txt";
$fp = fopen($filename, "r") or die("Cannot open the file!\n");
$buffer;
$buffer_tmp;

while(!feof($fp)) {
    $buffer_tmp = fgets($fp);
    if (!$buffer_tmp) break;
    $buffer = (int)$buffer_tmp;
    echo $buffer."\n";
}

//echo gettype($buffer);
echo "마지막으로 인식된 택배 상자의 개수는 ".$buffer."개 입니다.\n";
fclose($fp);
?>
