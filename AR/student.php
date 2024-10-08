<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'student_rewards');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student details
$sql = "SELECT name, school_id_number, points, profile_picture FROM students WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    $student = [
        'name' => 'Unknown',
        'school_id_number' => 'N/A',
        'points' => 0,
        'profile_picture' => 'default.jpg'
    ];
}

$code_error = '';
$code_class = '';
$code_value = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $entered_code = trim($_POST['code']);
    
    // Check if the code is valid and not redeemed
    $sql = "SELECT is_redeemed FROM reward_codes WHERE code=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $entered_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $code_info = $result->fetch_assoc();
        if ($code_info['is_redeemed'] == false) {
            // Update student's points
            $points = 1; // Each code is worth 1 point
            $sql = "UPDATE students SET points = points + ? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $points, $student_id);
            $stmt->execute();

            // Mark the code as redeemed
            $sql = "UPDATE reward_codes SET is_redeemed=TRUE WHERE code=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $entered_code);
            $stmt->execute();

            $code_error = 'Code redeemed successfully. Points added.';
            $code_class = 'success';
        } else {
            $code_error = 'This code has already been redeemed.';
            $code_class = 'error';
        }
    } else {
        $code_error = 'Invalid code. Please try again.';
        $code_class = 'error';
    }

    $stmt->close();
}

$conn->close();

// Function to determine badge based on points
function getBadge($points) {
    if ($points >= 100) {
        return "badge_gold.png";
    } elseif ($points >= 50) {
        return "badge_silver.png";
    } elseif ($points >= 20) {
        return "badge_bronze.png";
    } else {
        return "badge_no.png";
    }
}

$badge_image = getBadge($student['points']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
    <link rel="stylesheet" href="studentstyle.css"> <!-- Student-specific CSS -->
   
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
            <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h2>
        </div>

        <!-- Profile Container -->
        <div class="profile-container">
            <!-- Profile Picture -->
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($student['profile_picture']); ?>" alt="Profile Picture">
            </div>
            <!-- Profile Information -->
            <div class="profile-info">
                <h3>Student Details</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                <p><strong>ID Number:</strong> <?php echo htmlspecialchars($student['school_id_number']); ?></p>
                <p><strong>Points:</strong> <?php echo htmlspecialchars($student['points']); ?></p>
                <div class="badge">
                    <img src="<?php echo htmlspecialchars($badge_image); ?>" alt="Badge">
                </div>
            </div>
        </div>

       

        <!-- Home Page Post Section -->
        <h2>Latest News</h2>
        <div class="post">
            <h4>Leave No Trash Challenge</h4>
            <img src="1.jpg" alt="Post 1 Image">
            <p>Take the #LeaveNoTrash Challenge, win cool stuff!</p>
        </div>
        
    </div>

    <div class="menu-icon" onclick="toggleSidebar()">&#9776;</div>
</body>
</html>
