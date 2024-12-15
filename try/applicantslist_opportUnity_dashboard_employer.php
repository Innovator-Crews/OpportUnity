<?php
//eto yung magpopost ng 3 lang na latest job
$request = $_GET["user_id"];

$conn = new mysqli("localhost", "root", "", "opportunity");
//sql code when you want to display the 3 latest job post
$sql = "SELECT * FROM application_logs WHERE employer_userid = '$request' ORDER BY datetime_apply DESC LIMIT 3";

//sql code you want to display all the job post
// $sql = "SELECT * FROM job WHERE userid = '$request' ORDER BY datetime_job_created DESC";
$result = mysqli_query($conn, $sql);

$job = array();

while($row = mysqli_fetch_assoc($result))
{
    $job[] = $row;
}

echo json_encode($job);
?>