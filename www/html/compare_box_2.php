<?php
$star_file_path = "/var/www/html/".$_POST["id"]."/".$_POST["id"]."_star.txt";
if ($_POST['isFirst'] == 1) {
    // Get the number of delivered boxes from PaaS-TA
    if(isset($_POST['id'])){
        $user_id = $_POST['id'];
        $params = array ('id' => $user_id);
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


    /// Read page rendered as result of your POST request
    $num_delivered =  file_get_contents (
              'http://localhost/compare_box_1.php',  // page url
              false,
              $context);

    /// Create star.txt
    //$star_file_path = "/var/www/html/".$_POST["id"]."/".$_POST["id"]."_star.txt";
    $star_file = fopen($star_file_path, "w") or die("Unable to open file!");
    fwrite($star_file, "1");
    fclose($star_file);
}

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

sleep(2); // 2sec delay

// Count the number of detected boxes from detection result txt file
////$filename = "/home/wbb/darknet/result_201106.txt";
//$filename = "/var/www/html/".$_POST["id"]."/".$_POST["id"].".txt"; // 201108 수정 (실제 코드)
$filename = "/home/wbb/darknet/".$_POST["id"].".txt"; // 201108 수정 (임시 코드)
$fp = fopen($filename, "r") or die("Cannot open the file!\n");
$num_detected;
$num_detected_tmp;

while(!feof($fp)) {
    $num_detected_tmp = fgets($fp);
    if (!$num_detected_tmp) break;
    $num_detected = (int)$num_detected_tmp;
    //echo $num_detected."\n";
}

//echo "\nnum_detected = ".$num_detected."\n";
fclose($fp);

// Get the number of delivered boxes from PaaS-TA
if(isset($_POST['id'])){
$user_id = $_POST['id'];
$params = array ('uid' => $user_id);
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


/// Read page rendered as result of your POST request
$num_delivered =  file_get_contents (
              'http://finaltest.paasta.koren.kr/box_num.php',  // page url
              false,
              $context);
///$return = gettype($num_delivered);
$num_delivered_string = $num_delivered;
$num_delivered = (int)$num_delivered; //paasta에 배달 완료된 택배 개수

//Jetson에서 파스타로부터 가져온 택배 도착 개수를 파일에 저장, 덮어쓰기 하는 코드
//$file_path = "/var/www/html/examples.txt"; // 라즈베리파이 이미지가 저장되어있는 경로를 대입시켜야함. 소스코드가 없어 넣질 못하였습니다.
$file_path = "/var/www/html/".$_POST["id"]."/".$_POST["id"]."_pre.txt"; // 201108 수정

$is_file_exist = file_exists($file_path); // './blog/test.txt'
// 위 코드를 실행하는데 에러가 발생한다면, 하드 드라이브 쓰기 정보에 대한 PHP 파일 접근 권한을 확인하십시오.
if ($is_file_exist) {
    $fp = fopen($file_path, "r") or die("Cannot open the file!\n");
    while(!feof($fp)) {
        $num_delivered_tmp = fgets($fp);
        if (!$num_delivered_tmp) break;
        $num_delivered_pre = (int)$num_delivered_tmp;
    }
    fclose($fp);
    //echo 'Found it';
}
else {
    $myfile = fopen($file_path, "w") or die("Unable to open file!");
    $txt = $num_delivered_string;
    fwrite($myfile, $txt);
    //echo 'Not found.';
  }

//echo "num_delivered = ".$num_delivered."\n\n";
/// Server response is now stored in $result variable so you can process it
///echo($result);

// Compare the number of boxes 
$situation = 0;
// 1) 택배가 추가로 배달된 상황
if ($num_delivered > $num_delivered_pre) {
    if ($num_detected == $num_delivered) {
	//echo "추가 배달 후 num_detected == num_delivered\n택배가 정상적으로 배달되었습니다.\n";
	$situation = 1;
	echo $situation;
    }
    else {
	//echo "추가 배달 후 num_detected != num_delivered\n택배 배달 과정에서 문제가 발생하였습니다.\nQR코드 인식이 누락되었거나 택배 상자가 가려져 인식에 문제가 있습니다.\n";
	$situation = 2;
	echo $situation;

	// star.txt
	//$star_file_path = "/var/www/html/".$_POST["id"]."/".$_POST["id"]."_star.txt";
        $star_file = fopen($star_file_path, "w") or die("Unable to open file!");
        fwrite($star_file, "2");
        fclose($star_file);
    }

    $myfile = fopen($file_path, "w") or die("Unable to open file!");
    $txt = $num_delivered_string;
    fwrite($myfile, $txt);

}

// 2) 택배가 배달되지 않은 상황 
else if($num_delivered == $num_delivered_pre){
    // Read star from star.txt
    $star_file = fopen($star_file_path, "r") or die("Unable to open file!");
    while(!feof($star_file)) {
	$star_tmp = fgets($star_file);
	if (!$star_tmp) break;
	$star = (int)$star_tmp;
    }
    fclose($star_file);

    if ($star == 1) {
    if($num_detected > $num_delivered){
        //echo "택배원이 택배를 놓고있어 택배가 가려진 상황입니다.";
        $situation = 3;
        echo $situation;
    }
    else if ($num_detected < $num_delivered) {
	    //echo "num_detected < num_delivered\n도난 상황으로 판단됩니다.\n";
	    $situation = 4;
	    /*
	    if(isset($_POST['id'])){
		$user_id = $_POST['id'];
		$params = array ('uid' => $user_id);
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
	    $num_delivered =  file_get_contents (
	                  'http://finaltest.paasta.koren.kr/changeIsStolen.php',  // page url
           	    	  false,
	                  $context);
	    */
            echo $situation;
    }
    else {
	    //echo "num_detected == num_delivered\n정상 상황입니다.\n";
	    $situation = 5;
            echo $situation;
    }
    }

    else if ($num_detected == $num_delivered) {
	// 정상 상황
	$situation = 5;
	echo $situation;
	$star_file = fopen($star_file_path, "w") or die("Unable to open file!");
        fwrite($star_file, "1");
        fclose($star_file);
    }

    else if ($star == 2) {
	// 배달 과정에서 문제
	$situation = 2;
	echo $situation;
    }

    else if ($star == 0) {
	// 수령 중
	$situation = 0;
	echo $situation;
    }
} 

// 3) 택배가 수령되어 배달 완료된 택배 개수가 작아진 상황
else {
    $num_delivered_pre = $num_delivered;
    echo $situation;

    $myfile = fopen($file_path, "w") or die("Unable to open file!");
    $txt = $num_delivered_string;
    fwrite($myfile, $txt);

    // star.txt
    if ($num_detected != $num_delivered) {
	$star_file = fopen($star_file_path, "w") or die("Unable to open file!");
        fwrite($star_file, "0");
        fclose($star_file);
    }
}



// 201108) HTTP POST를 if문 밖으로. isStolen 대신 situation 값을 PaaS-TA로
if(isset($_POST['id'])){
    $user_id = $_POST['id'];
    $params = array ('uid' => $user_id, 'situation' => $situation);
} else { $params = array ('result' => 1000);}
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
$num_delivered =  file_get_contents (
		'http://finaltest.paasta.koren.kr/changeIsStolen.php',  // page url
		false,
		$context);


?>
