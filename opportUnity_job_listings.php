<?php
session_start();
include('db_connection.php'); // Make sure this file includes your database connection logic

// Check if the user is logged in and is an employee (job seeker)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: opportUnity_login.php");
    exit;
}

// Get all job posts
$query = "SELECT job_id, job_title, company_name, job_description FROM job_posts";
$result = $conn->query($query);

// Check for query error
if (!$result) {
    die("Error retrieving job listings: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job List - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_job_listing.css">
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

    <!-- Main Content -->
    <div class="container">
        <h1>Job Listings</h1>
        <div class="job-listings">
            <?php
            if ($result->num_rows > 0) {
                // Display the job listings
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="job-item">';
                    echo '<h3>' . htmlspecialchars($row['job_title']) . '</h3>';
                    echo '<p><strong>Company:</strong> ' . htmlspecialchars($row['company_name']) . '</p>';
                    echo '<p><strong>Description:</strong> ' . htmlspecialchars($row['job_description']) . '</p>';
                    echo '<a href="apply_job.php?job_id=' . $row['job_id'] . '" class="apply-btn">Apply</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No job listings available at the moment.</p>";
            }
            ?>
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

<?php
// Close the database connection
$conn->close();
?>
