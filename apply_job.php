<?php
// Start session to access session data
session_start();

// Include the database connection file
require 'db_connection.php';

// Check if the user is logged in and is an employee (job seeker)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    // Redirect to login page if not logged in or not a job seeker
    header("Location: opportUnity_login.html");
    exit;
}

// Check if the job_id is provided in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $user_id = $_SESSION['user_id']; // The logged-in user's ID

    // Prepare the query to check if the user has already applied for this job
    $query = "SELECT * FROM job_applications WHERE job_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $job_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user has already applied for the job, prevent re-application
    if ($result->num_rows > 0) {
        echo "You have already applied for this job.";
    } else {
        // Prepare the query to insert the job application
        $insertQuery = "INSERT INTO job_applications (job_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $job_id, $user_id);

        // Execute the query to insert the application
        if ($stmt->execute()) {
            echo "You have successfully applied for the job!";
            header("Location: opportUnity_dashboard_jobseeker.php");
        } else {
            echo "Error: Could not apply for the job. Please try again.";
            header("Location: opportUnity_job_listing.php");
        }
    }

    // Close the statement
    $stmt->close();
} else {
    // Redirect to the job listings page if no job_id is provided
    header("Location: opportUnity_job_listings.php");
    exit;
}
?>
