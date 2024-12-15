<?php
    // Start the session
    session_start();
$currentUrl = $_POST['currentUrl'];

    // Retrieve session variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;
    $job_position = $_POST['jobpos'];
    $companyname = $_POST['compName'];
    $firstname = $_POST['fname'];//
    $lastname = $_POST['lname'];//
    $jobid = $_POST['job_id'];
    $employer_id = $_POST['user_id'];

    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "INSERT INTO application_logs(jobid, employer_userid, job_position, company_name, first_name, last_name, jobseeker_userid, user_status) VALUES ('$jobid', '$employer_id', '$job_position', '$companyname', '$firstname', '$lastname', '$userID', 'Waiting List')";
    $result = mysqli_query($conn, $sql);

    //after the data is inserted into database the page will go back at opportUnity_dashboard_jobseeker.php
    header("Location: ".$currentUrl);
    exit();
?>