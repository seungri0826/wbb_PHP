<?php
echo 1;
$file = $_FILES['userfile'];
if (!$file) {
    http_response_code(700);
    return;
}
if (!is_dir("/".$_POST["id"])) {
    mkdir("./".$_POST["id"]);
}
move_uploaded_file($file["tmp_name"], "./".$_POST["id"]."/".$_POST["id"].".jpg");
