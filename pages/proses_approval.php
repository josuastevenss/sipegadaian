<?php
include '../config/database.php';
include '../middleware/auth.php';

checkLogin();
checkAdmin();

$id = $_GET['id'];
$aksi = $_GET['aksi'];

if($aksi == 'approve'){
    $status = 'approved';
} else {
    $status = 'rejected';
}

mysqli_query($conn, "UPDATE pengajuan SET status='$status' WHERE id='$id'");

header("Location: approval.php");