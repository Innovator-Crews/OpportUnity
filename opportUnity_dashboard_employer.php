<?php
// Start session to access session data
session_start();
require 'db_connection.php';

// Get the user details from session
$currentUser = [
    'first_name' => $_SESSION['first_name'],  // Assuming you store the first name in session
    'role' => $_SESSION['role']               // Add role from session to handle role-specific features
];

// Check if user is logged in and is an employer
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'employer') {
    // Fetch job posts from the database for the logged-in employer
    $employerId = $_SESSION['user_id'];
    
    $query = "SELECT * FROM job_posts WHERE employer_id = ? ORDER BY date_posted DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $employerId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all job posts
    $jobPosts = [];
    while ($row = $result->fetch_assoc()) {
        $jobPosts[] = $row;
    }

    // Fetch job applications for each job post, along with job details
    $jobApplications = [];
    foreach ($jobPosts as $job) {
        $query = "SELECT users.first_name, users.last_name, users.email, job_posts.job_title, job_posts.company_name
                  FROM job_applications
                  JOIN users ON job_applications.user_id = users.id
                  JOIN job_posts ON job_applications.job_id = job_posts.job_id
                  WHERE job_applications.job_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $job['job_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        // Store applicants for the current job post, with job title and company name
        $applicants = [];
        while ($row = $result->fetch_assoc()) {
            $applicants[] = $row;
        }
        $jobApplications[$job['job_id']] = $applicants; // Associate applicants with each job
    }
} else {
    // Redirect to login page if not logged in or not an employer
    header("Location: opportUnity_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_dashboard_employer.css">
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
            <a href="opportUnity_postjob.php" class="post-job-btn">
                <button>Post a Job</button>
            </a>

            <h2>Your Job Posts</h2>
                <?php if (count($jobPosts) > 0): ?>
                    <?php foreach ($jobPosts as $job): ?>
                        <div class="job-post">
                            <h3><?php echo htmlspecialchars($job['job_title']); ?></h3>
                            <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company_name']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['job_location']); ?></p>
                            <p><strong>Salary:</strong> $<?php echo number_format($job['salary'], 2); ?></p>
                            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($job['job_description'])); ?></p>
                            <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($job['requirements'])); ?></p>
                            <p><strong>Qualities:</strong> <?php echo nl2br(htmlspecialchars($job['qualities'])); ?></p>
                            <p><strong>Expectations:</strong> <?php echo nl2br(htmlspecialchars($job['expectations'])); ?></p>

                            <!-- Edit Button Form -->
                            <form action="edit_job.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $job['job_id']; ?>">
                                <button type="submit">Edit</button>
                            </form>

                            <!-- Delete Button Form -->
                            <form action="delete_job.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?')">
                                <input type="hidden" name="id" value="<?php echo $job['job_id']; ?>">
                                <button type="submit">Delete Job</button>
                            </form>

                            <!-- Applicants List -->
                            <h4>Applicants</h4>
                            <?php if (isset($jobApplications[$job['job_id']]) && count($jobApplications[$job['job_id']]) > 0): ?>
                                <ul>
                                    <?php foreach ($jobApplications[$job['job_id']] as $applicant): ?>
                                        <li>
                                            <strong><?php echo htmlspecialchars($applicant['first_name']) . ' ' . htmlspecialchars($applicant['last_name']); ?></strong> 
                                            - <?php echo htmlspecialchars($applicant['email']); ?> 
                                            <br>
                                            <strong>Applying to:</strong> <?php echo htmlspecialchars($applicant['job_title']) . " at " . htmlspecialchars($applicant['company_name']); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>No applicants yet.</p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No job posts found. Create your first job post!</p>
                <?php endif; ?>

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
                // Close the dropdown if the click is outside the user menu
                dropdown.style.display = 'none';
            }
        });

        // Add event listener to username to toggle the dropdown
        document.getElementById('userName').addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent click from propagating to the window
            toggleDropdown(); // Toggle the dropdown visibility
        });

        function logout() {
            const confirmLogout = confirm("Are you sure you want to log out?");
            if (confirmLogout) {
                // Redirect to the logout.php to handle session destruction
                window.location.href = "logout.php";  // This triggers the logout process
            }
        }
    </script>
</body>
</html>
