<?php
include 'config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user']['role'] == 'admin') {
    header("Location: dashboard/admin.php");
} else {
    header("Location: dashboard/user.php");
}
?>