<?php
$uid = $_POST['userid'];
$conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "UPDATE `admin` SET AccountState = 'active', AccountState_datetime = NOW() WHERE user_ID = '".$uid."'";
$result = mysqli_query($conn, $sql);
header("Location: admin.php");
exit;
?>