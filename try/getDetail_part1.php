<?php
//eto yung magpopost ng lahat na latest job
session_start();
$uid = $_SESSION["id"];

$conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "SELECT * FROM application_logs WHERE jobseeker_userid = '$uid'";

//sql code you want to display all the job post
// $sql = "SELECT * FROM job WHERE userid = '$uid' ORDER BY datetime_job_created DESC";
$result = mysqli_query($conn, $sql);

$job = array();

while($row = mysqli_fetch_assoc($result))
{
    $job[] = $row;
}
$_SESSION["joblist"] = $job;
?>