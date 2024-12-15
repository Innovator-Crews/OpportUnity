<?php
$request = $_GET["user_id"];

$conn = new mysqli("localhost", "root", "", "opportunity");
//sql code when you want to display the 3 latest job post
$sql = "SELECT * FROM job WHERE userid = '$request' ORDER BY datetime_job_created DESC LIMIT 3";

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