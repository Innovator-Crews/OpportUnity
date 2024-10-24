<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    </style>
</head>
<body>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
             <input type="text" name="fullname" placeholder="Enter Name">
             <input type="file" name="image">
             <input type="submit" value="upload" name="submit">
             <input type="submit" name="display">
        </form>
    </div>
</body>
</html>

<?php
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "upload_image";

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if(isset($_POST['submit']))
{
    $nm = $_POST['fullname'];
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
    $query = "INSERT INTO imageee(imag, nam) VALUES ('$image', '$nm')";
    mysqli_query($conn, $query);
}
if(isset($_POST['display']))
{
    $query = "SELECT * FROM imageee";
    $as = mysqli_query($conn, $query);
    echo"<table><tr>";
    while($row=mysqli_fetch_array($as))
    {
        echo"<td>";
        echo"<center>".$row['nam']."</center>";
        echo"<img src='data:image/jpeg;base64,".base64_encode($row['imag'])."' height='100px' width='100px'";
        echo"</td>";
    }
    echo"</tr></table>";
}
?>