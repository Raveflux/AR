<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle post submission with image
if (isset($_POST['post_content'])) {
    $post_content = $_POST['post_content'];
    $posted_by = $_SESSION['username'];
    $image = NULL;

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            echo "<p>File is not an image.</p>";
            $uploadOk = 0;
        }

        // Check file size (limit to 5MB)
        if ($_FILES["image"]["size"] > 5000000) {
            echo "<p>Sorry, your file is too large.</p>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<p>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
            $uploadOk = 0;
        }

        // Attempt to upload file
        if ($uploadOk === 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "<p>The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.</p>";
        } else {
            echo "<p>Sorry, there was an error uploading your file.</p>";
        }
    }
    
    if (!empty($post_content)) {
        $stmt = $conn->prepare("INSERT INTO posts (content, posted_by, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $post_content, $posted_by, $image);
        
        if ($stmt->execute()) {
            echo "<p>Post added successfully.</p>";
        } else {
            echo "<p>Error adding post: " . $conn->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Content cannot be empty.</p>";
    }
}

// Handle deletion
if (isset($_GET['delete'])) {
    $id_to_delete = intval($_GET['delete']);
    
    $delete_sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id_to_delete);
    
    if ($stmt->execute()) {
        echo "<p>User deleted successfully.</p>";
    } else {
        echo "<p>Error deleting user: " . $conn->error . "</p>";
    }
    $stmt->close();
}

// Pagination and search logic
$results_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $results_per_page;

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

$count_sql = "SELECT COUNT(*) AS total FROM students";
if (!empty($search_query)) {
    $count_sql .= " WHERE name LIKE ?";
}
$count_stmt = $conn->prepare($count_sql);
if (!empty($search_query)) {
    $search_param = "%" . $search_query . "%";
    $count_stmt->bind_param("s", $search_param);
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $results_per_page);

$sql = "SELECT id, name, school_id_number, points FROM students";
if (!empty($search_query)) {
    $sql .= " WHERE name LIKE ?";
}
$sql .= " LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
if (!empty($search_query)) {
    $stmt->bind_param("sii", $search_param, $results_per_page, $offset);
} else {
    $stmt->bind_param("ii", $results_per_page, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Get all posts
$post_sql = "SELECT content, posted_by, created_at, image FROM posts ORDER BY created_at DESC";
$post_result = $conn->query($post_sql);

if (!$post_result) {
    die("Query failed: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="adminstyles.css">
    <style>
        /* Styles for the post form */
        .post-form {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .post-form input[type="file"] {
            margin-bottom: 10px;
        }

        .post-form button {
            background: #96140a;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.3s, transform 0.3s;
        }

        .post-form button:hover {
            background: #c81010;
            transform: scale(1.05);
        }

        .posts {
            margin: 20px;
        }

        .post {
            padding: 10px;
            background-color: #fff;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .post img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }
    </style>
    <script>
        function resetSearch() {
            document.getElementsByName('search')[0].value = '';
            document.forms[0].submit();
        }
    </script>
</head>
<body>
    <div class="header">
        <h2>Admin Page</h2>
        <button onclick="location.href='logout.php'" class="logout-button">Log Out</button>
    </div>
    
    <div class="post-form">
        <form method="post" enctype="multipart/form-data">
            <textarea name="post_content" placeholder="Write something..."></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post</button>
        </form>
    </div>
    
    <div class="search-bar">
        <form method="get" action="admin.php">
            <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit">Search</button>
            <button type="button" onclick="resetSearch()">Filter</button>
        </form>
    </div>
    
    <table>
        <tr>
            <th>Name</th>
            <th>School ID Number</th>
            <th>Points</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . htmlspecialchars($row["name"]) . "</td>
                    <td>" . htmlspecialchars($row["school_id_number"]) . "</td>
                    <td>" . htmlspecialchars($row["points"]) . "</td>
                    <td>
                        <a href='view_profile.php?id=" . htmlspecialchars($row["id"]) . "' class='view-button'>View Profile</a> |
                        <a href='?delete=" . htmlspecialchars($row["id"]) . "' class='delete-button' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No students found</td></tr>";
        }
        ?>
    </table>
    
    <div class="pagination">
        <?php
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "&search=" . urlencode($search_query) . "'>Prev</a> ";
        }

        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='?page=$i&search=" . urlencode($search_query) . "'>$i</a> ";
            }
        }

        if ($page < $total_pages) {
            echo "<a href='?page=" . ($page + 1) . "&search=" . urlencode($search_query) . "'>Next</a>";
        }
        ?>
    </div>

    <div class="posts">
        <h3>Recent Posts</h3>
        <?php while ($post = $post_result->fetch_assoc()): ?>
            <div class="post">
                <p><strong><?php echo htmlspecialchars($post['posted_by']); ?></strong> - <?php echo date("F j, Y, g:i a", strtotime($post['created_at'])); ?></p>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                <?php if ($post['image']): ?>
                    <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
