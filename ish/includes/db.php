<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = '127.0.0.1';
$db = 'dcs_news';
$user = 'root'; // Default username for XAMPP/WAMP
$pass = '';     // Default password for XAMPP/WAMP

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
