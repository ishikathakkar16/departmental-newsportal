<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
include '../includes/auth.php';

// Check if the user is logged in and is an admin
if (getUserRole() !== 'admin') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - DCS News</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>DCS News - Admin Dashboard</h1>
    </header>
    <nav>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_posts.php">Manage Posts</a>
    <a href="add_posts.php">Add Posts</a>
    <a href="../logout.php">Logout</a>
</nav>

    <main>
        <h2>Welcome, Admin</h2>
        <p>Here you can manage the news portal, including users, posts, and categories.</p>
    </main>
    <footer>
        <p>&copy; 2024 DCS News</p>
    </footer>
</body>
</html>
