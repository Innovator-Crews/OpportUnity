<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_postjob.css">
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

    <div id="postjob-container">
        <h2>Post a Job</h2>
        <form id="postjob-form" action="post_job.php" method="POST">
            <div class="form-group">
                <label for="job-title">Job Title</label>
                <input type="text" id="job-title" name="job_title" required>
            </div>

            <div class="form-group">
                <label for="company-name">Company Name</label>
                <input type="text" id="company-name" name="company_name" required>
            </div>

            <div class="form-group">
                <label for="job-location">Location</label>
                <input type="text" id="job-location" name="job_location" required>
            </div>

            <div class="form-group">
                <label for="job-description">Job Description</label>
                <textarea id="job-description" name="job_description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" id="salary" name="salary" required>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="qualities">Qualities</label>
                <textarea id="qualities" name="qualities" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="expectations">Expectations</label>
                <textarea id="expectations" name="expectations" rows="3" required></textarea>
            </div>

            <button type="submit" class="submit-btn">Post Job</button>
        </form>
    </div>

    <script>
        // JavaScript to toggle the dropdown visibility
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close the dropdown if clicked outside
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
                alert("You have successfully logged out!");
                window.location.href = "opportUnity_login.php";
            }
        }
    </script>

</body>
</html>
