<?php
session_start();
require 'db_connection.php';

// Check if user is logged in and is an employer
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'employer') {
    if (isset($_POST['id'])) {  // Use POST if submitting via form
        $jobId = $_POST['id'];

        // Check if the job exists and belongs to the logged-in employer
        $query = "SELECT * FROM job_posts WHERE job_id = ? AND employer_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $jobId, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $job = $result->fetch_assoc();

        if ($job) {
            // Delete the job post from the database
            $deleteQuery = "DELETE FROM job_posts WHERE job_id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $jobId);
            $deleteStmt->execute();

            // Redirect back to the employer dashboard after successful deletion
            header("Location: opportUnity_dashboard_employer.php");
            exit;
        } else {
            // Job not found or doesn't belong to the employer
            header("Location: opportUnity_dashboard_employer.php");
            exit;
        }
    } else {
        // No job id provided, redirect back
        header("Location: opportUnity_dashboard_employer.php");
        exit;
    }
} else {
    // Redirect to login page if not logged in or not an employer
    header("Location: opportUnity_login.html");
    exit;
}
?>
