<?php
session_start();
$userID = $_SESSION['id'] ?? null;
//this will run if a user is posted a job
if (isset($_POST['makejobjob_title'])) {
    $jobpos = $_POST['job_title'];
    $compname = $_POST['company_name'];
    $jobloc = $_POST['job_location'];
    $jobdes = $_POST['job_description'];
    $jobsal = $_POST['salary'];
    $jobreq = $_POST['requirements'];
    $jobqual = $_POST['qualities'];
    $jobexpect = $_POST['expectations'];
    $jobLimit = $_POST['limit'];
    $jobStat = "Hiring";
    $job_type = $_POST['job_type'];
    $work_schedule_preference = $_POST['work_schedule_preference'];
    $company_industry_type = $_POST['company_industry_type'];

    $jobID = 0; // Initialize jobID

    $conn = new mysqli("localhost", "root", "", "opportunity");

    if ($conn) {
        do {
            $jobID = rand(100000000, 999999999);
            $sql = "SELECT * FROM job WHERE jobid=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $jobID);
            $stmt->execute();
            $result = $stmt->get_result();
        } while ($result->num_rows > 0);
        

        $sql = "INSERT INTO job (jobid, companyname, jobname, jobdesc, job_location, salary, requirements, qualities, expectation, userid, job_limit, job_status, job_type, work_schedule_preference, company_industry_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssssiissss", $jobID, $compname, $jobpos, $jobdes, $jobloc, $jobsal, $jobreq, $jobqual, $jobexpect, $userID, $jobLimit, $jobStat, $job_type, $work_schedule_preference, $company_industry_type);
        

        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);

        if ($stmt->execute()) {
            header('Location: opportUnity_dashboard_employer.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Connection failed: " . mysqli_connect_error();
    }
}
?>

