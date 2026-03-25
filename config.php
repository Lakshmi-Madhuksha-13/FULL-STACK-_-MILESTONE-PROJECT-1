<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shopsphere';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Cart functions
function getCartCount() {
    return isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}
?>