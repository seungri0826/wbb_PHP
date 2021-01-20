<?php
$host = 'localhost';
$username = 'wbb';
$password = 'dhkdqkaQkd18!';
$dbname = 'rbpi';
$port = 80;

$conn = mysqli_connect($host, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("conncetion failed:" . $conn->connect_error);
}

$query = "SELECT * FROM raspimg";
$result = mysqli_query($conn, $query);

while ($data = mysqli_fetch_array($result)) {
    echo '<img src="data:image/jpeg;base64,'.base64_encode( $data['image'] ).'"/>';
}

mysqli_close($conn);
?>
