<?php
// Start the session
session_start();
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;
require 'connection.php';

$currentUrl = $_POST['currentUrl'];

// Check if user is logged in and is an employer
if (isset($_SESSION['id']) && $_SESSION['usertype'] === 'employer') {
    if (isset($_POST['id'])) {  // Use POST if submitting via form
        $jobId = $_POST['id'];

        // Check if the job exists and belongs to the logged-in employer
        $query = "SELECT * FROM job WHERE jobid = ? AND userid = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $jobId, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $job = $result->fetch_assoc();

        if ($job) {
            // Delete the job post from the database
            $deleteQuery = "DELETE FROM job WHERE jobid = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $jobId);
            $deleteStmt->execute();

            // Delete the job post from the database
            $deleteQuery = "DELETE FROM application_logs WHERE jobid = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $jobId);
            $deleteStmt->execute();

            // Redirect back to the employer dashboard after successful deletion
            header("Location: ".$currentUrl);
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