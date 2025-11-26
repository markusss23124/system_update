


<?php

session_start();
include("connection_db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== "Admin") {
    header("Location: /mak/login-admin.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM menu_tbl WHERE dish_id = '$id'";
    $conn->query($sql);
}

header("Location: /mak/crud-admin.php");
exit();

?>
