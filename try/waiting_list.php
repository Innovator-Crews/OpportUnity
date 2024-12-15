<?php
session_start();
$request = $_GET["user_id"];
$_SESSION['user_statusDistributerzz'] = "waiting_list";

$conn = new mysqli("localhost", "root", "", "opportunity");
// dati gumagana to nung u.user_ID, u.user_firstname, u.user_lastname, u.datetime_user_created palang laman ng user
// $sql = "SELECT u.* FROM user u INNER JOIN application_logs al ON u.user_ID = al.jobseeker_userid WHERE al.jobid = '".$request."' AND al.user_status = 'Waiting List' ORDER BY al.datetime_apply DESC;";
$sql = "SELECT u.user_ID, u.user_firstname, u.user_lastname, u.datetime_user_created FROM user u INNER JOIN application_logs al ON u.user_ID = al.jobseeker_userid WHERE al.jobid = '".$request."' AND al.user_status = 'Waiting List' ORDER BY al.datetime_apply DESC;";
$result = mysqli_query($conn, $sql);

$job = array();

while($row = mysqli_fetch_assoc($result))
{
    $job[] = $row;
}

echo json_encode($job);
?>