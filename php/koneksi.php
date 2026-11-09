<?php
$koneksi = new mysqli("localhost", "root", "", "db_ecommerce");

if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}

$koneksi->set_charset("utf8mb4");
?>
