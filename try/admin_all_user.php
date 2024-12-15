<?php
//eto yung magpopost ng lahat na latest job
// $request = $_GET["user_id"];

$conn = new mysqli("localhost", "root", "", "opportunity");
//sql code when you want to display the 3 latest job post
$sql = "SELECT u.user_ID, u.user_type, u.user_firstname, u.user_lastname, u.user_username, u.user_sex, u.datetime_user_created, u.date_of_birth, u.age, u.use_phone_number, a.user_last_login, a.AccountState FROM admin a JOIN user u ON a.user_ID = u.user_ID ORDER BY a.user_last_login ASC;";

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