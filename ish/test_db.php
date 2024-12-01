<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'localhost';
$db = 'dcs_news'; // Make sure this is the correct database name
$user = 'root';   // Default username for XAMPP MySQL
$pass = '';       // Default password for XAMPP MySQL

// Try to establish a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
} else {
    echo 'Database connection successful!';
}
?>
