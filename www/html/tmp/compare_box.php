<?php

// Execute darknet
$out = shell_exec("cd /home/wbb/darknet;./darknet detect cfg/yolov3_box.cfg backup/yolov3_box_5000.weights"); 

// Count the number of detected boxes from detection result txt file
$filename = "/home/wbb/darknet/result_201017.txt";
$fp = fopen($filename, "r") or die("Cannot open the file!\n");
$num_detected;
$num_detected_tmp;

while(!feof($fp)) {
    $num_detected_tmp = fgets($fp);
    if (!$num_detected_tmp) break;
    $num_detected = (int)$num_detected_tmp;
    echo $num_detected."\n";
}

echo "\nnum_detected = ".$num_detected."\n";
fclose($fp);

// Get the number of delivered boxes from PaaS-TA
if(isset($_POST['id'])){
$user_id = $_POST['id'];
$params = array ('user' => '$user_id');
}else{ $params = array ('result' => 1000);}
$query = http_build_query ($params);

/// Create Http context details
$contextData = array ( 
            'method' => 'POST',
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n".
			"Connection: close\r\n".
                        "Content-Length: ".strlen($query)."\r\n",
            'content'=> $query );

/// Create context resource for our request
$context = stream_context_create (array ( 'http' => $contextData ));

/// 이전 배달된 택배 개수
$num_delivered_pre = 3;
/// Read page rendered as result of your POST request
$num_delivered =  file_get_contents (
              'http://dbconphp.paasta.koren.kr/box_num.php',  // page url
              false,
              $context);
///$return = gettype($num_delivered);
$num_delivered = (int)$num_delivered;
echo "num_delivered = ".$num_delivered."\n\n";
/// Server response is now stored in $result variable so you can process it
///echo($result);

// Compare the number of boxes (while loop 안에서 실행될 것)
$situation = 0;
// 1) 택배가 추가로 배달된 상황
if ($num_delivered > $num_delivered_pre) {
    if ($num_detected == $num_delivered) {
	echo "추가 배달 후 num_detected == num_delivered\n택배가 정상적으로 배달되었습니다.\n";
	$situation = 1;
    }
    else {
	echo "추가 배달 후 num_detected != num_delivered\n택배 배달 과정에서 문제가 발생하였습니다.\nQR코드 인식이 누락되었거나 택배 상자가 가려져 인식에 문제가 있습니다.\n";
	$situation = 2;
    }
}

// 2) 택배가 배달되지 않은 상황 (3번이랑 합쳐서 생각하면 될 듯) ==> 도난 상황 판단만 하면 됨
else {
    // $num_detected != $num_delivered가 나을지, $num_detected < $num_delivered가 나을지?
    if ($num_detected < $num_delivered) {
	echo "num_detected < num_delivered\n도난 상황으로 판단됩니다.\n";
	$situation = 3;
    }
    else {
	echo "num_detected >= num_delivered\n정상 상황입니다.\n";
	$situation = 4;
    }
}

// 3) 택배가 수령되어 배달 완료된 택배 개수가 작아진 상황

$num_delivered_pre = $num_delivered;
echo $situation;


/* 이전 코드
if ($num_detected < $num_delivered) {
    if ($num_delivered > $num_delivered_pre) {
	echo "방금 추가로 배달된 택배 상자가 기존 배달된 택배 상자를 가려 상자 인식에 어려움이 있습니다.\n위치를 조정해주세요.\n";
    }
    else if ($num_delivered == $num_delivered_pre) {
	echo "배달된 택배 상자 ".($num_delivered - $num_detected)."개가 인식되지 않습니다. 도난상황인 것 같습니다.\n";
    }
}
else if ($num_detected > $num_delivered) {
    echo "배송 완료한 택배 중 QR코드 인식이 누락된 택배가 있는지 확인해주세요.\n";
}
else {
    echo "정상 상태입니다.\n";
}

$num_delivered_pre = $num_delivered;
*/

// 모두 while loop 안으로 들어갈 것
// darknet 실행 부분은 다른 php 프로그램으로 분리하는 것이 좋을듯
?>
