<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shop_giay";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  echo("Kết nối thất bại: " . $conn->connect_error);
  exit();
}
?>
