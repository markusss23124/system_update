<?php
session_start();
session_unset();
session_destroy();
header("Location: /mak/login-admin.php");
exit();
?>
