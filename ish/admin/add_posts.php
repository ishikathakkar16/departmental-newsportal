<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../includes/db.php';  
include '../includes/auth.php';



if (!in_array(getUserRole(), ['admin', 'editor'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $description = $_POST['description'];
    $author_id = $_SESSION['user_id']; // Logged-in user ID
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads";  
        if (!is_dir($targetDir)) {
            echo "Directory does not exist. Trying to create it...<br>";
            if (!mkdir($targetDir, 0777, true)) {
                echo "Error creating directory.<br>";
                exit();
            } else {
                echo "Directory created successfully.<br>";
            }
        } else {
            echo "Directory already exists.<br>";
        }
        
        $image = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . '/' . $image;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
        // Validate image type
        echo "File type: " . $fileType . "<br>";
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $validExtensions)) {
            // Check if the file was uploaded successfully
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                echo "File uploaded successfully: " . $image . "<br>";
            } else {
                echo "Error uploading file. Check if the file exists and permissions are correct.<br>";
                exit();
            }
        } else {
            echo "Invalid file type for image upload.<br>";
            exit();
        }
    } else {
        echo "No file selected.<br>";
    }

    // Insert post into the database
    $image = !empty($image) ? $image : null; // Set image to null if not provided

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO posts (title, category_id, description, author_id, image) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        echo "Failed to prepare SQL statement: " . $conn->error;
        exit();
    }

    // Bind parameters to prevent SQL injection
    $stmt->bind_param('sssis', $title, $category_id, $description, $author_id, $image);

    // Execute the query and check if the post was added successfully
    if ($stmt->execute()) {
        echo "Post added successfully!";
        header('Location: ../admin/manage_posts.php');
        exit();
    } else {
        echo "Error executing query: " . $stmt->error . "<br>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Post - DCS News</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <h1>Add Post</h1>
        <nav>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_posts.php">Manage Posts</a>
    <a href="add_posts.php">Add Posts</a>
    <a href="../logout.php">Logout</a>
</nav>
    </header>
    <form method="POST" action="add_post.php" enctype="multipart/form-data">
        <label for="title">Post Title:</label>
        <input type="text" id="title" name="title" required>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <?php
            // Fetch categories from the database
            $categories = $conn->query("SELECT * FROM categories");
            if ($categories) {
                while ($category = $categories->fetch_assoc()) {
                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                }
            } else {
                echo "Error fetching categories: " . $conn->error;
            }
            ?>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="5" required></textarea>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image">

        <button type="submit">Add Post</button>
    </form>
</body>
</html>
