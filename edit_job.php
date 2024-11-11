<?php
session_start();
require 'db_connection.php';

// Check if user is logged in and is an employer
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'employer') {
    if (isset($_GET['id'])) {
        $jobId = $_GET['id'];

        // Fetch the job details from the database
        $query = "SELECT * FROM job_posts WHERE job_id = ? AND employer_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $jobId, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $job = $result->fetch_assoc();

        if (!$job) {
            // Job not found or doesn't belong to the employer
            header("Location: opportUnity_dashboard_employer.php");
            exit;
        }
    } else {
        // No job id provided, redirect back
        header("Location: opportUnity_dashboard_employer.php");
        exit;
    }

    // Handle form submission to update job post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jobTitle = $_POST['job_title'];
        $companyName = $_POST['company_name'];
        $jobLocation = $_POST['job_location'];
        $salary = $_POST['salary'];
        $description = $_POST['job_description'];
        $requirements = $_POST['requirements'];
        $qualities = $_POST['qualities'];
        $expectations = $_POST['expectations'];

        // Update job post in the database
        $updateQuery = "UPDATE job_posts SET job_title = ?, company_name = ?, job_location = ?, salary = ?, job_description = ?, requirements = ?, qualities = ?, expectations = ? WHERE job_id = ? AND employer_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssdssssii", $jobTitle, $companyName, $jobLocation, $salary, $description, $requirements, $qualities, $expectations, $jobId, $_SESSION['user_id']);
        $updateStmt->execute();

        // Redirect back to the employer dashboard after successful update
        header("Location: opportUnity_dashboard_employer.php");
        exit;
    }
} else {
    // Redirect to login page if not logged in or not an employer
    header("Location: opportUnity_login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_postjob.css">
</head>
<body>

    <div id="postjob-container">
        <h2>Edit Job Post</h2>

        <form method="POST">
            <label for="job_title">Job Title:</label>
            <input type="text" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required><br>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" value="<?php echo htmlspecialchars($job['company_name']); ?>" required><br>

            <label for="job_location">Location:</label>
            <input type="text" name="job_location" value="<?php echo htmlspecialchars($job['job_location']); ?>" required><br>

            <label for="salary">Salary:</label>
            <input type="text" name="salary" value="<?php echo htmlspecialchars($job['salary']); ?>" required><br>

            <label for="job_description">Description:</label>
            <textarea name="job_description" required><?php echo htmlspecialchars($job['job_description']); ?></textarea><br>

            <label for="requirements">Requirements:</label>
            <textarea name="requirements" required><?php echo htmlspecialchars($job['requirements']); ?></textarea><br>

            <label for="qualities">Qualities:</label>
            <textarea name="qualities" required><?php echo htmlspecialchars($job['qualities']); ?></textarea><br>

            <label for="expectations">Expectations:</label>
            <textarea name="expectations" required><?php echo htmlspecialchars($job['expectations']); ?></textarea><br>

            <button type="submit">Update Job</button>
        </form>
    </div>

</body>
</html>
