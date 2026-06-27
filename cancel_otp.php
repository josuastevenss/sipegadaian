<?php
session_start();

// hapus session OTP saja
unset($_SESSION['otp_user']);
unset($_SESSION['debug_otp']);

// kembali ke login
header("Location: login.php");
exit;