<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';  // Ensure you include your database connection
include '../includes/auth.php';

// Ensure only admins or editors can access this page
if (!in_array(getUserRole(), ['admin', 'editor'])) {
    header('Location: login.php');
    exit();
}

// Get the post ID from the URL
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
} else {
    echo "Post ID is required!";
    exit();
}

// Fetch the existing post details
$post = $conn->query("SELECT * FROM posts WHERE id = $post_id")->fetch_assoc();
if (!$post) {
    echo "Post not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get updated post details
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];
    $image = $post['image'];  // Keep the existing image by default
    
    // Check if a new image has been uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../admin/uploads";  // Ensure the directory path is correct

        // Delete the old image if a new one is uploaded
        if ($post['image'] && file_exists($targetDir . '/' . $post['image'])) {
            unlink($targetDir . '/' . $post['image']);
        }
        
        // Upload the new image
        $image = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . '/' . $image;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Validate image type
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $validExtensions)) {
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                echo "Error uploading the file.";
                exit();
            }
        } else {
            echo "Invalid file type for image upload.";
            exit();
        }
    }

    // Update the post details in the database
    $stmt = $conn->prepare("UPDATE posts SET title = ?, category_id = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $title, $category_id, $description, $image, $post_id);

    if ($stmt->execute()) {
        header('Location: manage_posts.php');  // Redirect to manage posts page
        exit();
    } else {
        echo "Error updating post: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Edit Post</h1>
    </header>
    <form method="POST" action="edit_post.php?id=<?php echo $post['id']; ?>" enctype="multipart/form-data">
        <label for="title">Post Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <?php
            // Fetch categories from the database
            $categories = $conn->query("SELECT * FROM categories");
            if ($categories) {
                while ($category = $categories->fetch_assoc()) {
                    $selected = ($category['id'] == $post['category_id']) ? 'selected' : '';
                    echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                }
            } else {
                echo "Error fetching categories: " . $conn->error;
            }
            ?>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($post['description']); ?></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image">

        <?php if ($post['image']) { ?>
            <p>Current image: <img src="../admin/<?php echo htmlspecialchars($post['image']); ?>" width="100" alt="Current Image"></p>
        <?php } ?>

        <button type="submit">Update Post</button>
    </form>
</body>
</html>
