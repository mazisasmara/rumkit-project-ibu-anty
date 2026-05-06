<?php
// config.php - Konfigurasi Database RS Hermana Makassar

$host = "0.0.0.0";
$user = "root";
$password = "root";
$database = "rs_hermana";

// Koneksi ke database
$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8mb4");

// Fungsi utility
function sanitize($input) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($input));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function checkSession($requiredRole = null) {
    if (!isLoggedIn()) {
        redirect('login.php');
    }
    if ($requiredRole && getUserRole() !== $requiredRole) {
        redirect('index.php');
    }
}
?>
