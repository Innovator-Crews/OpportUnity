<?php

    session_start();

    $request = $_GET["user_id"];
    $conn = new mysqli("localhost", "root", "", "opportunity");
        
    // Fetch job IDs from application_logs
    $sql = "SELECT j.* FROM job j INNER JOIN application_logs al ON j.jobid = al.jobid WHERE al.jobseeker_userid = '".$request."' ORDER BY datetime_apply DESC;";
    // $sql = "SELECT j.* FROM job j INNER JOIN application_logs al ON j.jobid = al.jobid WHERE al.jobseeker_userid = '".$_SESSION['id']."' AND j.job_status = 'Hiring' ORDER BY datetime_apply DESC;";
    $result = mysqli_query($conn, $sql);

    
    $job = array();
    while($row = mysqli_fetch_assoc($result)) {
        $job[] = $row;
    }

    echo json_encode($job);
?>