<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['role'] ?? null;
}

function redirectToRoleDashboard() {
    $role = getUserRole();
    if ($role === 'admin') {
        header('Location: admin/dashboard.php');
    } elseif ($role === 'editor') {
        header('Location: editor/dashboard.php');
    } else {
        header('Location: user/view_posts.php');
    }
    exit();
}
?>
