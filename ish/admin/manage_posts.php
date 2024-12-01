<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';
include '../includes/auth.php';

if (!in_array(getUserRole(), ['admin', 'editor'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM posts WHERE id = $id");
    header('Location: manage_posts.php');
}

$posts = $conn->query("SELECT posts.*, categories.name AS category, users.username AS author 
                       FROM posts 
                       JOIN categories ON posts.category_id = categories.id 
                       JOIN users ON posts.author_id = users.id");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Posts</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Manage Posts</h1>
    <a href="add_posts.php">Add New Post</a>
    <nav>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_posts.php">Manage Posts</a>
    <a href="add_posts.php">Add Posts</a>
    <a href="../logout.php">Logout</a>
</nav>
    
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Actions</th>
        </tr>
        <?php while ($post = $posts->fetch_assoc()): ?>
            <tr>
                <td><?= $post['title'] ?></td>
                <td><?= $post['category'] ?></td>
                <td><?= $post['author'] ?></td>
                <td>
                    <a href="edit_post.php?id=<?= $post['id'] ?>">Edit</a>
                    <a href="manage_posts.php?delete=<?= $post['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
