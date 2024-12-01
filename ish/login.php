<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Check if the form is being submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debugging: Check if the form is being submitted
    echo "Form submitted!";

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Include the DB connection and authentication functions
    include 'includes/db.php';  // Include the DB connection
    include 'includes/auth.php'; // Include authentication functions

    // Example: Database query to check if the username exists
    $stmt = $conn->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Check if password is correct
        if (password_verify($password, $user['password'])) {
            // Set session variables for user ID and role
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on user role
            if ($_SESSION['role'] === 'admin') {
                header('Location: admin/dashboard.php');  // Redirect to admin dashboard
                exit();
            } else {
                header('Location: users/view_posts.php');  // Redirect to user dashboard
                exit();
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DCS News</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h2>Login to DCS News</h2>
    
    <form action="login.php" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
        
    </form>
    <p>Don't have an account? <a href="signup.php">Signup here</a></p>

</body>
</html>
