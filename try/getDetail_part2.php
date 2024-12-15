<?php
// Start the session
include 'getDetail_part1.php';
$request = $_GET["jobid"];

// Retrieve the job list from the session getDetail_part1
$joblist = $_SESSION["joblist"] ?? null;

// Check if the job list is not null and contains elements
if ($joblist && count($joblist) > 0) {
    // Loop through the job list
    foreach ($joblist as $job) {
        // Check if the job ID matches the session job ID
        if ($job['jobid'] == $request) {
            // Output the user status
            echo $job['user_status'];
        }
    }
} else {
    echo "No job information available.";
}
?>
