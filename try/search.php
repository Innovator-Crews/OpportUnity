<?php
session_start(); // Start the session

$request = $_GET["jobSearch"];

$conn = new mysqli("localhost", "root", "", "opportunity");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];

    // Fetch job IDs from application_logs
    $sql = "SELECT jobid FROM application_logs WHERE jobseeker_userid = '$user_id' ORDER BY datetime_apply DESC";
    $result = mysqli_query($conn, $sql);

    $list_jobid = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $list_jobid[] = $row['jobid'];
    }
    $list_jobid_str = implode(', ', $list_jobid);

    // Fetch jobs based on the condition
    if (empty($list_jobid)) {
        $sql_jobs = "SELECT * FROM job WHERE job_status = 'Hiring' ORDER BY datetime_job_created DESC";
    } else {
        $sql_jobs = "SELECT j.* FROM job j LEFT JOIN application_logs al ON j.jobid = al.jobid AND al.jobseeker_userid = $user_id WHERE (j.jobid NOT IN ($list_jobid_str) OR al.jobid IS NULL) AND (j.job_status = 'Hiring') ORDER BY datetime_job_created DESC";
    }

    $result_jobs = mysqli_query($conn, $sql_jobs);

    $job = array();
    $lowercase_req = strtolower($request);

    while ($row = mysqli_fetch_assoc($result_jobs)) {
        $job[] = $row;
    }

    // Filter the job array to find matches
    $find = array_filter($job, function($job) use ($lowercase_req) {
        return stripos(strtolower($job['jobname']), $lowercase_req) !== false;
    });

    echo json_encode(array_values($find));
} else {
    echo json_encode([]);
}
?>