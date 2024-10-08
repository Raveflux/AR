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

// Prepare and execute query for rankings
$sql = "SELECT students.id, name, points, profile_picture FROM students ORDER BY points DESC";
$result = $conn->query($sql);

$rankings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rankings[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 5 Ranking Leaderboard</title>
    <link rel="stylesheet" href="studentstyles.css"> <!-- General CSS -->
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

        /* Ranking styles */
        .ranking-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            margin: 50px auto;
        }

        .ranking-container h3 {
            color: #333;
            margin-top: 20px;
        }

        .ranking-container ul {
            list-style: none;
            padding: 0;
        }

        .ranking-container li {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .ranking-container .trophy {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .ranking-container .rank {
            font-size: 24px;
            font-weight: bold;
            color: #96140a;
            margin-right: 15px;
        }

        .ranking-container img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 15px;
        }

        .ranking-container .name {
            font-size: 18px;
            color: #333;
            flex: 1;
        }

        .ranking-container .points {
            font-size: 18px;
            color: #96140a;
        }

        /* Sidebar open state */
        .sidebar-open {
            left: 0;
        }

        .content-open {
            margin-left: 250px;
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
            <h2>Top 5 Ranking Leaderboard</h2>
        </div>

        <!-- Ranking Container -->
        <div class="ranking-container">
            <h3>Top Students</h3>
            <ul>
                <?php foreach ($rankings as $index => $student) : ?>
                    <?php if ($index >= 5) break; // Stop after displaying top 5 students ?>
                    <li>
                        <!-- Display trophy icon for top 3 ranks -->
                    
                        <div class="rank"><?php echo $index + 1; ?></div>
                        <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
                        <div class="name"><?php echo htmlspecialchars($student['name']); ?></div>
                        <div class="points"><?php echo htmlspecialchars($student['points']); ?> Points</div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
