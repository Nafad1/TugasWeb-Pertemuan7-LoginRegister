<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: login.php?msg=logged_out');
exit;
?>