<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
include '../includes/auth.php';

// Check if the 'id' parameter is present in the URL and is a valid integer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = $_GET['id'];  // Get the category ID from the URL

    // Fetch category name
    $category_result = $conn->query("SELECT name FROM categories WHERE id = $category_id");
    
    if ($category_result->num_rows > 0) {
        $category = $category_result->fetch_assoc();
        
        // Fetch posts for the selected category
        $posts = $conn->query("SELECT * FROM posts WHERE category_id = $category_id ORDER BY created_at DESC");
    } else {
        echo "Category not found.";
        exit();
    }
} else {
    echo "Invalid category ID.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category - <?php echo $category['name']; ?> - DCS News</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <h1>DCS News - <?php echo $category['name']; ?> Posts</h1>
        <nav>
            <a href="view_posts.php">Home</a>
            <a href="category_posts.php">Categories</a>
            <a href="about_us.php">About Us</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Posts in <?php echo $category['name']; ?></h2>
        
        <div class="posts">
            <?php if ($posts->num_rows > 0): ?>
                <?php while ($post = $posts->fetch_assoc()) { ?>
                    <div class="post">
                        <h3><?php echo $post['title']; ?></h3>
                        <p><?php echo substr($post['description'], 0, 200); ?>...</p>
                        <a href="view_post.php?id=<?php echo $post['id']; ?>">Read More</a>
                    </div>
                <?php } ?>
            <?php else: ?>
                <p>No posts available in this category.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 DCS News</p>
    </footer>

</body>
</html>
