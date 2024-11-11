<?php
$request = $_GET["user_id"];

    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM job WHERE userid = '$request'";
    $result = mysqli_query($conn, $sql);

    $job = array();

    while($row = mysqli_fetch_assoc($result))
    {
        $job[] = $row;
    }

    echo json_encode($job);

?>