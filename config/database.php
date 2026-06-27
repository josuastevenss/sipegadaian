<?php
$conn = mysqli_connect("localhost", "root", "", "sippegadaian");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>