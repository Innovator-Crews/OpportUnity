<?php
    $id = $_POST['id'];
    $datee = $_POST['petsa'];
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "INSERT INTO trylang(id, petsa) VALUES ('$id', '$datee')";
    $result = mysqli_query($conn, $sql);
?>