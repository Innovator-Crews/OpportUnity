<?php
// Start session to access session data
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: opportUnity_login.html");
    exit;
}

// Get the user details from session
$currentUser = [
    'first_name' => $_SESSION['first_name'],  // Assuming you store the first name in session
    'role' => $_SESSION['role']               // Add role from session to handle role-specific features
];

// Include the database connection
require 'db_connection.php';

// Query to fetch job posts (assuming job posts table exists with relevant columns)
$query = "SELECT job_id, job_title, job_description, requirements, qualities, expectations, employer_id FROM job_posts";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all job posts
$jobPosts = [];
while ($row = $result->fetch_assoc()) {
    $jobPosts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_dashboard_jobseeker.css">
    <link rel="icon" type="image/png" href="faviconlogo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Nav bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <a href="opportUnity.html"><img src="logo.png" alt="OpportUnity Logo" class="navbar-logo"></a>
            <a href="opportUnity.html"><div class="logo">OpportUnity</div></a>
            <ul class="nav-links">
                <li><a href="opportUnity.html">Landing Page</a></li>
                <li><a href="#">Terms & Condition</a></li>
            </ul>
        </div>
        <div class="navbar-right">
            <div id="userMenu" class="user-menu">
                <span id="userName">Welcome, <?php echo $_SESSION['first_name']; ?>!</span>
                <div id="dropdown" class="dropdown-content">
                    <a href="profile.html">View Profile</a>
                    <a href="#" onclick="logout()">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div>
        <h1>OpportUnity</h1>
        <div id="container">
            <h2>Dashboard</h2>
            <h2>Available Jobs</h2>
            <div id="job-list-container">
                <?php if (count($jobPosts) > 0): ?>
                    <?php foreach ($jobPosts as $job): ?>
                        <div class="job-card">
                        <h3><?php echo $job['job_title']; ?></h3>
                            <p><strong>Description:</strong> <?php echo $job['job_description']; ?></p>
                            <p><strong>Requirements:</strong> <?php echo $job['requirements']; ?></p>
                            <p><strong>Qualifications:</strong> <?php echo $job['qualities']; ?></p>
                            <p><strong>Expectations:</strong> <?php echo $job['expectations']; ?></p>
                            <button onclick="window.location.href='apply_job.php?id=<?php echo $job['job_id']; ?>'">Apply Now</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No job posts available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle the dropdown visibility
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // JavaScript to close the dropdown if the user clicks outside of it
        window.addEventListener('click', function(event) {
            var dropdown = document.getElementById('dropdown');
            var userMenu = document.getElementById('userMenu');
            if (!userMenu.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        document.getElementById('userName').addEventListener('click', function(event) {
            event.stopPropagation();
            toggleDropdown();
        });

        function logout() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
