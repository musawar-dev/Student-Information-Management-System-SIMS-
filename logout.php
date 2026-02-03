<?php
session_start();
include 'db_connect.php';

// Logout ka log record karein
if (isset($_SESSION['user_id'])) {
    log_activity($_SESSION['user_id'], $_SESSION['role'], 'LOGOUT', 'User logged out');
}

session_unset();
session_destroy();
header("Location: login.php");
exit();
?>