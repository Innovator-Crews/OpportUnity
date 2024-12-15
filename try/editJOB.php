<?php
// Start the session
session_start();

// Retrieve session variables
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;

// Retrieve POST variables
$jobid = $_POST['job_id'];

echo $jobid;
$company_name = $_POST['company_name'];
$job_title = $_POST['job_title'];
$job_description = $_POST['job_description'];
$job_location = $_POST['job_location'];
$salary = $_POST['salary'];
$requirements = $_POST['requirements'];
$qualities = $_POST['qualities'];
$expectations = $_POST['expectations'];
$job_type = $_POST['job_type'];
$work_schedule_preference = $_POST['work_schedule_preference'];
$company_industry_type = $_POST['company_industry_type'];

// Create a new MySQLi connection
$conn = new mysqli("localhost", "root", "", "opportunity");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement
$sql = "UPDATE job SET 
    companyname = ?, jobname = ?, jobdesc = ?, job_location = ?, salary = ?,
    requirements = ?, qualities = ?, expectation = ?, job_type = ?, work_schedule_preference = ?, 
    company_industry_type = ? WHERE jobid = ?";

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
$stmt->bind_param("sssssisssssi", 
    $company_name, $job_title, $job_description, $job_location, $salary,
    $requirements, $qualities, $expectations, $job_type, $work_schedule_preference,
    $company_industry_type, $jobid
);

// Execute the statement
$stmt->execute();

// Redirect to the profile page
header("Location: opportUnity_dashboard_employer.php");
exit;
?>
