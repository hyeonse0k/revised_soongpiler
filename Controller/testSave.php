<?php
$data = $_POST['photo'];
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
mkdir("C:\Bitnami\wampstack-7.2.30-0\apache2\htdocs\Controller/photos");
file_put_contents("C:\Bitnami\wampstack-7.2.30-0\apache2\htdocs\Controller"."/photos/"."user_#".time().'.png', $data);
die;
?>
