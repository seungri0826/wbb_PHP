<?php
if (!isset($_FILES['userfile'])) {
    echo '<p>Please select a file</p>';
} else {
    try {
        upload();
        /*** give praise and thanks to the php gods ***/
        echo '<p>Thank you for submitting</p>';
    } catch (Exception $e) {
        echo '<h4>' . $e->getMessage() . '</h4>';
    }
}
function upload() {
    $Picture = $_FILES['userfile'];
    $id = $_POST['id'];
    var_dump($Picture);
    $PSize = filesize($Picture["tmp_name"]);
    var_dump($PSize);
    $mysqlPicture = addslashes(fread(fopen($Picture["tmp_name"], 'rb'), $PSize));

$conn = mysqli_connect(
  'localhost',
  'wbb',
  'dhkdqkaQkd18!',
  'rbpi');

    mysqli_set_charset($conn, "utf-8");
    $insert_query = "INSERT INTO raspimg (image, image_name) VALUES ('$mysqlPicture', '$id') ";
    mysqli_query($conn, $insert_query);
    mysqli_close($conn);
}
?>
