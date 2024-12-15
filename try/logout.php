<?php
include 'connection.php';
session_start();
$userID = $_SESSION['id'];
$sql = "UPDATE admin SET user_last_login=current_timestamp()	 WHERE user_ID = '".$userID."'";
$result = mysqli_query($conn, $sql);

session_unset();
echo "You have been logged out";
?>
