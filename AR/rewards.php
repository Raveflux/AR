<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current page number from the URL, default to 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Number of students to show per page
$offset = ($current_page - 1) * $limit; // Calculate the offset for SQL query

// Prepare and execute query for rankings with LIMIT and OFFSET
$sql = "SELECT students.id, name, points, profile_picture FROM students ORDER BY points DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Fetch rankings for the current page
$rankings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

// Get total number of students for pagination
$total_sql = "SELECT COUNT(*) as total FROM students";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_students = $total_row['total'];
$total_pages = ceil($total_students / $limit); // Calculate total pages

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rewards Page</title>
 
    <link rel="stylesheet" href="studentstyles.css"> <!-- Student-specific CSS -->
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            height: 100vh;
            padding: 0;
        }

        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 250px;
            background-color: #96140a;
            position: fixed;
            top: 0;
            left: -250px;
            transition: 0.3s;
            padding-top: 20px;
            z-index: 999;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #c81010;
        }

        .sidebar h3 {
            color: white;
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-icon {
            font-size: 30px;
            cursor: pointer;
            color: #96140a;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }

        .content {
            margin-left: 10px;
            padding: 20px;
            flex-grow: 1;
            transition: margin-left 0.3s;
        }

        /* Rewards styles */
        .rewards-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
        }

        .rewards-container h3 {
            color: #333;
            margin-top: 20px;
        }

        .rewards-container ul {
            list-style: none;
            padding: 0;
        }

        .rewards-container li {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .rewards-container img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
        }

        .rewards-container .name {
            font-size: 18px;
            color: #333;
            flex: 1;
        }

        .rewards-container .points {
            font-size: 18px;
            color: #96140a;
        }

        /* Sidebar open state */
        .sidebar-open {
            left: 0;
        }

        .content-open {
            margin-left: 50px;
        }

        /* Pagination styles */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #96140a;
            border: 1px solid #96140a;
            border-radius: 5px;
            transition: background-color 0.3s;
            color: white;
        }

        .pagination a:hover {
            background-color: #c81010;
            color: white;
        }

        .active {
            background-color: #96140a;
            color: white;
            border: 1px solid #96140a;
        }
    </style>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('sidebar-open');
            content.classList.toggle('content-open');
        }
    </script>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h2>Ranking Leaderboard</h2>
        </div>

        <!-- Rewards Container -->
        <div class="rewards-container">
            <h3>Top Students</h3>
            <ul>
                <?php foreach ($rankings as $student) : ?>
                    <li>
                        <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
                        <div class="name"><?php echo htmlspecialchars($student['name']); ?></div>
                        <div class="points"><?php echo htmlspecialchars($student['points']); ?> Points</div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Pagination -->
            <div class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $current_page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</body>
</html>
