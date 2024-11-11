<?php
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM job";
    $result = mysqli_query($conn, $sql);

    $job = array();

    while($row = mysqli_fetch_assoc($result))
    {
        $job[] = $row;
    }

    echo json_encode($job);
?>