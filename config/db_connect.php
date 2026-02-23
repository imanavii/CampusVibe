<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "campusvibe";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB Connection failed");
}
?>
echo "DB OK";