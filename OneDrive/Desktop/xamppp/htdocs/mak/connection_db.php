<?php
// connection_db.php
$server = "localhost";
$user = "root";
$pass = "";
$db_name = "restaurant_db";

$conn = mysqli_connect($server, $user, $pass, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
