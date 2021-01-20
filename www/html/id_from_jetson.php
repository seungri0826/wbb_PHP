<?php
if(isset($_POST['id'])){
$user_id = $_POST['id'];
$params = array ('user' => '$user_id');
}else{ $params = array ('result' => 1000);}
$query = http_build_query ($params);

// Create Http context details
$contextData = array ( 
            'method' => 'POST',
            'header' => "Connection: close\r\n".
                        "Content-Length: ".strlen($query)."\r\n",
            'content'=> $query );

// Create context resource for our request
$context = stream_context_create (array ( 'http' => $contextData ));

// Read page rendered as result of your POST request
$result =  file_get_contents (
              'http://dbconphp.paasta.koren.kr/box_num.php',  // page url
              false,
              $context);
$result = (int)$result;
$return = gettype($result);
echo($return);
// Server response is now stored in $result variable so you can process it
echo($result);
?>
