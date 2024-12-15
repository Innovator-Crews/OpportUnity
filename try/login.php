<?php
include 'connection.php';

$input_username = $_POST['email'];
$input_password = $_POST['password'];

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

session_start();
$_SESSION['username'] = "";
$_SESSION['password'] = "";
$_SESSION['usertype'] = "";
$_SESSION['firstname'] = "";
$_SESSION['lastname'] = "";
$_SESSION['id'] = "";

$found = false;

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        if ($input_username == $row['user_username'] && $input_password == $row['user_password']) {
            $_SESSION['username'] = $input_username;
            $_SESSION['password'] = $input_password;
            $_SESSION['usertype'] = $row['user_type'];
            $_SESSION['firstname'] = $row['user_firstname'];
            $_SESSION['lastname'] = $row['user_lastname'];
            $_SESSION['id'] = $row['user_ID'];
            $found = true;

            if ($_SESSION['usertype'] == "employer") {
                $sql = "UPDATE admin SET AccountState = 'active', user_last_login = NOW() WHERE user_ID = '".$_SESSION['id']."'";
                $result = mysqli_query($conn, $sql);
                header('Location: opportUnity_dashboard_employer.php');
                exit();
            } else {
                $sql = "UPDATE admin SET AccountState = 'active' AND user_last_login = NOW() WHERE user_ID = '".$_SESSION['id']."'";
                header('Location: opportUnity_dashboard_jobseeker.php');
                exit();
            }
        }
    }
}

if (!$found) { 
    $_SESSION['error'] = "Incorrect email or password."; 
    header('Location: opportUnity_login.php'); 
    exit(); 
}
?>