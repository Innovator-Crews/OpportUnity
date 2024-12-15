<?php
    session_start();

    $conn = new mysqli("localhost", "root", "", "opportunity");
    
    // Fetch job IDs from application_logs
    $sql = "SELECT jobid FROM application_logs WHERE jobseeker_userid = '".$_SESSION['id']."' ORDER BY datetime_apply DESC";
    $result = mysqli_query($conn, $sql);
    
    $list_jobid = [];
    while($row = mysqli_fetch_assoc($result)) {
        $list_jobid[] = $row['jobid'];
    }
    $list_jobid_str = implode(', ', $list_jobid);
    
    // Fetch jobs based on the condition
    if(empty($list_jobid)) {
        $sql = "SELECT * FROM job WHERE job_status = 'Hiring' ORDER BY datetime_job_created DESC LIMIT 3";
    } else {
        //eto yung responsible kaya di nakikita ng user yung job after nya mag apply
        $sql = "SELECT j.* FROM job j LEFT JOIN application_logs al ON j.jobid = al.jobid AND al.jobseeker_userid = ".$_SESSION['id']." WHERE (j.jobid NOT IN (" . $list_jobid_str . ") OR al.jobid IS NULL) AND (j.job_status = 'Hiring') ORDER BY datetime_job_created DESC LIMIT 3";
    }
    
    $result = mysqli_query($conn, $sql);
    
    // Process the result as needed
    $job = array();
    while($row = mysqli_fetch_assoc($result)) {
        $job[] = $row;
    }
    
    echo json_encode($job);
    
    /*
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM job ORDER BY datetime_job_created DESC";
    $result = mysqli_query($conn, $sql);

    $job = array();

    while($row = mysqli_fetch_assoc($result))
    {
        $job[] = $row;
    }

    echo json_encode($job);
    */
?>