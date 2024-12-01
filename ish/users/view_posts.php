<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
include '../includes/auth.php';

// Only allow admin or editor access
if (!in_array(getUserRole(), ['admin', 'editor', 'user'])) {  // You can allow users too if needed
    header('Location: ../login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Posts - DCS News</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>DCS News</h1>
    </header>
    
    <nav>
        <!-- Add Home, Categories, About Us, and other links -->
        <a href="view_posts.php">Home</a>  <!-- Link to home page -->
        <a href="view_posts.php">View Posts</a>  <!-- Link to View Posts -->
        <a href="category_posts.php">Categories</a>  <a href="category_posts.php"></a>
        <a href="about_us.php">About Us</a>  <a href="about_us.php"></a>
        <a href="../logout.php">Logout</a>  <!-- Link to Logout -->
    </nav>
    
    <main>
        <h2>Posts</h2>
        <p>Below are the latest posts:</p>
        
        <?php
        // Retrieve posts from the database
        $sql = "SELECT posts.id, posts.title, posts.description, posts.image, categories.name AS category_name
                FROM posts
                LEFT JOIN categories ON posts.category_id = categories.id
                ORDER BY posts.created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='post'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><strong>Category:</strong> " . htmlspecialchars($row['category_name']) . "</p>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";

                if ($row['image']) {
                    echo "<img src='../uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['title']) . "' />";
                }

                echo "<a href='view_post.php?id=" . $row['id'] . "'>Read More</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </main>
    
    <footer>
        <p>&copy; 2024 DCS News</p>
    </footer>
</body>
</html>
