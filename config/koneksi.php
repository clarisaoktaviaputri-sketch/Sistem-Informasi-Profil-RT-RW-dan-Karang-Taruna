<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_profilrt";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function cek_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}

function cek_admin() {
    cek_login();
    if ($_SESSION['role'] != 'admin') {
        die("Akses ditolak.");
    }
}

function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>