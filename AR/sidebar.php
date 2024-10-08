<!-- Sidebar -->
<div id="sidebar" class="sidebar">
    <h3>Student Dashboard</h3>
    <a href="student.php">Profile</a>
    <a href="javascript:void(0)" onclick="openRedeemModal()">Redeem Code</a>
    <a href="ranking.php">TOP 5 </a>
    <a href="rewards.php">Over All Leaderboard</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Hamburger Icon -->
<div id="menu-icon" class="menu-icon" onclick="toggleSidebar()">
    &#9776; <!-- Hamburger icon -->
</div>

<!-- Modal for Redeem Code -->
<div id="redeemModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRedeemModal()">&times;</span>
        <h2>Redeem Your Code</h2>
        <form action="redeem_code.php" method="POST">
    <label for="code">Enter Reward Code:</label>
    <input type="text" id="code" name="code" required>
    <button type="submit">Redeem</button>
</form>
        <?php if (!empty($code_error)): ?>
            <p class="<?php echo htmlspecialchars($code_class); ?>"><?php echo htmlspecialchars($code_error); ?></p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Open modal function
    function openRedeemModal() {
        document.getElementById('redeemModal').style.display = 'block';
    }

    // Close modal function
    function closeRedeemModal() {
        document.getElementById('redeemModal').style.display = 'none';
    }

    // Close modal if clicked outside
    window.onclick = function(event) {
        const modal = document.getElementById('redeemModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>

<style>
    /* Modal styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
        max-width: 500px; /* Maximum width */
        border-radius: 10px; /* Rounded corners */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
    }
</style>
