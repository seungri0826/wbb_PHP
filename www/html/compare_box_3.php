<?php
while(true) {
	// Count the number of detected boxes from detection result txt file
	$filename = "/home/wbb/darknet/result_201106.txt";
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

	// 실험용 임시 num_delivered, num_delivered_pre 값
	$num_delivered = 4;
	$num_delivered_pre = 4;
	
	$file_path = "/var/www/html/examples.txt";
	// Compare the number of boxes 
	$situation = 0;
	
	// 1) 택배가 추가로 배달된 상황
	if ($num_delivered > $num_delivered_pre) {
	    if ($num_detected == $num_delivered) {
		echo "추가 배달 후 num_detected == num_delivered\n택배가 정상적으로 배달되었습니다.\n";
		$situation = 1;
		echo $situation;
	    }
	    else {
		echo "추가 배달 후 num_detected != num_delivered\n택배 배달 과정에서 문제가 발생하였습니다.\nQR코드 인식이 누락되었거나 택배 상자가 가려져 인식에 문제가 있습니다.\n";
		$situation = 2;
		echo $situation;
	    }

	    $myfile = fopen($file_path, "w") or die("Unable to open file!");
	    $txt = $num_delivered_string;
	    //fwrite($file_path, $txt);
	    fwrite($myfile, $txt);
	}

	// 2) 택배가 배달되지 않은 상황 
	else if($num_delivered == $num_delivered_pre){
	    if($num_detected > $num_delivered){
	        echo "택배원이 택배를 놓고있어 택배가 가려진 상황입니다.";
	        $situation = 3;
	        echo $situation;
	    }
	    else if ($num_detected < $num_delivered) {
		    echo "num_detected < num_delivered\n도난 상황으로 판단됩니다.\n";
		    $situation = 4;
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

	            echo $situation;
	    }
	    else {
		    echo "num_detected == num_delivered\n정상 상황입니다.\n";
		    $situation = 5;
	            echo $situation;
	    }
	} 

	// 3) 택배가 수령되어 배달 완료된 택배 개수가 작아진 상황
	else {
	    $num_delivered_pre = $num_delivered;
	    echo $situation;

	    $myfile = fopen($file_path, "w") or die("Unable to open file!");
	    $txt = $num_delivered_string;
	    //fwrite($file_path, $txt);
	    fwrite($myfile, $txt);
	}
	sleep(5);
}
?>
