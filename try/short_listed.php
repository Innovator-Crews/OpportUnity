<?php
session_start();
$_SESSION['user_statusDistributerzz'] = "short_listed";
$request = $_GET["user_id"];

$conn = new mysqli("localhost", "root", "", "opportunity");
// $sql = "SELECT u.* FROM user u INNER JOIN application_logs al ON u.user_ID = al.jobseeker_userid WHERE al.jobid = '".$request."' AND al.user_status = 'Short Listed' ORDER BY al.datetime_apply DESC;";
$sql = "SELECT u.user_ID, u.user_firstname, u.user_lastname, u.datetime_user_created FROM user u INNER JOIN application_logs al ON u.user_ID = al.jobseeker_userid WHERE al.jobid = '".$request."' AND al.user_status = 'Short Listed' ORDER BY al.datetime_apply DESC;";
$result = mysqli_query($conn, $sql);

$job = array();

while($row = mysqli_fetch_assoc($result))
{
    $job[] = $row;
}

echo json_encode($job);
?>