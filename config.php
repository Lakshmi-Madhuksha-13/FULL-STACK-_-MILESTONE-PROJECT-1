<?php
session_start();

// ===== ADD THIS FOR RAILWAY =====
if (getenv('RAILWAY_ENVIRONMENT')) {
    session_save_path('/tmp');
}
// ================================

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shopsphere';

// ===== ADD THIS FOR RAILWAY =====
if (getenv('MYSQL_URL')) {
    $url = parse_url(getenv('MYSQL_URL'));
    $host = $url['host'];
    $username = $url['user'];
    $password = $url['pass'];
    $database = ltrim($url['path'], '/');
}
// ================================

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>