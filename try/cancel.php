<?php
// Start the session
session_start();
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$jobseeker_userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;
require 'connection.php';
$currentUrl = $_POST['currentUrl'];

// Check if user is logged in and is an employer
if (isset($_SESSION['id']) && $_SESSION['usertype'] === 'employee') {
    if (isset($_POST['id'])) {  // Use POST if submitting via form
        $jobId = $_POST['id'];

        
            // Delete the application from the database
            $deleteQuery = "DELETE FROM application_logs WHERE jobid = ? AND jobseeker_userid = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("ii", $jobId, $jobseeker_userID);
            $deleteStmt->execute();

            // Redirect back to the employer dashboard after successful deletion
    header("Location: ".$currentUrl);
            exit;
        
    } else {
        // No job id provided, redirect back
        header("Location: opportUnity_dashboard_employer.php");
        exit;
    }
} 
else {
    // Redirect to login page if not logged in or not an jobseeker
    header("Location: opportUnity_login.html");
    exit;
}
?>
