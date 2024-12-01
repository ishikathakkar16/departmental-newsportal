<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session itself
session_destroy();

// Redirect to the login page
header('Location: login.php');
exit();
