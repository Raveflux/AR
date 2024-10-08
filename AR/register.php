<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username_error = '';
$school_id_error = '';
$school_id_format_error = '';
$password_error = '';
$password_match_error = '';
$success_message = '';
$profile_picture_error = '';

$name = '';
$birthdate = '';
$school_id_number = '';
$username = '';
$password = '';
$confirm_password = ''; // New variable for confirm password
$profile_picture = '';

const SCHOOL_ID_PREFIX = 'SCC-';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $school_id_number = $_POST['school_id_number'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password']; // Get confirm password value

    // Handle image upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        $file_name = basename($_FILES['profile_picture']['name']);
        $upload_file = $upload_dir . $file_name;

        // Validate image file type and size here if needed

        if (move_uploaded_file($tmp_name, $upload_file)) {
            $profile_picture = $upload_file;
        } else {
            $profile_picture_error = "Failed to upload profile picture";
        }
    } else {
        $profile_picture_error = "Profile picture is required";
    }

    // Validate school ID number format
    if (!preg_match('/^' . preg_quote(SCHOOL_ID_PREFIX, '/') . '\d{2}-\d+$/', $school_id_number)) {
        $school_id_format_error = "School ID Number must start with '" . SCHOOL_ID_PREFIX . "' and be in the format 'SCC-xx-xxxx'";
    }

    // Validate password length and match
    if (strlen($password) < 5) {
        $password_error = "Password must contain at least 5 characters";
    } elseif ($password !== $confirm_password) {
        $password_match_error = "Passwords do not match";
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    // Database connection
    if (empty($school_id_format_error) && empty($password_error) && empty($password_match_error) && empty($profile_picture_error)) {
        $conn = new mysqli('localhost', 'root', '', 'student_rewards');

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if username already exists
        $sql = "SELECT id FROM students WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $username_error = "Username already exists";
        }

        // Check if school ID number already exists
        $sql = "SELECT id FROM students WHERE school_id_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $school_id_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $school_id_error = "School ID Number already exists";
        }

        // Only insert into the database if there are no errors
        if (empty($username_error) && empty($school_id_error)) {
            $sql = "INSERT INTO students (name, birthdate, school_id_number, username, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $name, $birthdate, $school_id_number, $username, $password, $profile_picture);

            if ($stmt->execute()) {
                $success_message = "New record created successfully";
            } else {
                $success_message = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="regstyles.css">
    <script>
        function calculateAge() {
            var birthdateInput = document.getElementById('birthdate');
            var birthdate = new Date(birthdateInput.value);
            var today = new Date();
            var age = today.getFullYear() - birthdate.getFullYear();
            var monthDiff = today.getMonth() - birthdate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        }

        function resetCalendar() {
            document.getElementById('birthdate').value = '';
            document.getElementById('age').value = '';
        }

        function validateForm() {
            var schoolId = document.getElementById('school_id_number').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            var profilePicture = document.getElementById('profile_picture').files.length;
            var regex = /^SCC-\d{2}-\d+$/;
            var schoolIdErrorField = document.getElementById('school_id_format_error');
            var passwordErrorField = document.getElementById('password_error');
            var confirmPasswordErrorField = document.getElementById('confirm_password_error');
            var profilePictureErrorField = document.getElementById('profile_picture_error');

            var valid = true;

            if (!regex.test(schoolId)) {
                schoolIdErrorField.textContent = "School ID Number must start with 'SCC-' and be in the format 'SCC-xx-xxxx'";
                schoolIdErrorField.style.display = "block";
                valid = false;
            } else {
                schoolIdErrorField.style.display = "none";
            }

            if (password.length < 5) {
                passwordErrorField.textContent = "Password must contain at least 5 characters";
                passwordErrorField.style.display = "block";
                valid = false;
            } else {
                passwordErrorField.style.display = "none";
            }

            if (password !== confirmPassword) {
                confirmPasswordErrorField.textContent = "Passwords do not match";
                confirmPasswordErrorField.style.display = "block";
                valid = false;
            } else {
                confirmPasswordErrorField.style.display = "none";
            }

            if (profilePicture === 0) {
                profilePictureErrorField.textContent = "Profile picture is required";
                profilePictureErrorField.style.display = "block";
                valid = false;
            } else {
                profilePictureErrorField.style.display = "none";
            }

            return valid;
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form method="post" action="register.php" enctype="multipart/form-data" onsubmit="return validateForm();">
            <?php if (!empty($success_message)) : ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Removed user type field -->

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($birthdate); ?>" required onchange="calculateAge();">
            <button type="button" onclick="resetCalendar();">Reset Calendar</button><br>

            <input type="hidden" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>">

            <p class="error-text" id="age_error" style="display: none;"></p>

            <label for="school_id_number">School ID Number:</label>
            <input type="text" id="school_id_number" name="school_id_number" value="<?php echo htmlspecialchars($school_id_number); ?>" class="<?php echo !empty($school_id_format_error) ? 'input-error' : ''; ?>" required><br>
            <p class="error-text" id="school_id_format_error" style="<?php echo !empty($school_id_format_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $school_id_format_error; ?></p>
            <p class="error-text" id="school_id_error" style="<?php echo !empty($school_id_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $school_id_error; ?></p> <!-- Added error display for school ID -->

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="<?php echo !empty($username_error) ? 'input-error' : ''; ?>" required><br>
            <p class="error-text" id="username_error" style="<?php echo !empty($username_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $username_error; ?></p>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="<?php echo !empty($password_error) ? 'input-error' : ''; ?>" required><br>
            <p class="error-text" id="password_error" style="<?php echo !empty($password_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $password_error; ?></p>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="<?php echo !empty($password_match_error) ? 'input-error' : ''; ?>" required><br>
            <p class="error-text" id="confirm_password_error" style="<?php echo !empty($password_match_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $password_match_error; ?></p>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" required>
            <p class="error-text" id="profile_picture_error" style="<?php echo !empty($profile_picture_error) ? 'display: block;' : 'display: none;'; ?>"><?php echo $profile_picture_error; ?></p>

            <button type="submit">Register</button>
        </form>
        <p class="login-link">
            Already have an account? <a href="login.php">Login here</a>.
        </p>
    </div>
</body>
</html>
