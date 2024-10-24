<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
        .backbtn{
            height:10%;
            width: 90%;
            display:flex;
            align-items:center;
            font-weight: bold;
            color:black;
            font-size:25px;
        }
        form{
            height:90%;
            width: 100%;
            display:flex;
            flex-direction:column;
            justify-content:space-around;
            align-items:center;
        }
        .signupfrm{
            height:500px;
            width: 400px;
            backdrop-filter: blur(10px);
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            border-radius:10px;
            box-shadow:0px 0px 5px black;
        }
        .btnspc{
            height:90px;
            width: 300px;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        body{
            height:100vh;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            background:url("bgdes.jpg");
            background-size:cover;
        }
        a{
            text-decoration:none;
            color:black;
        }
        .gendiv{
            display:flex;
            justify-content:space-between;
            align-items:center;
            width: 60%;
        }
        .inputs{
            width: 250px;
            height: 40px;
            border-radius:5px;
            border:2px solid white;
        }
        .btn{
            width: 250px;
            height: 40px;
            border-radius:5px;
            background-color:Transparent;
            transition:0.5s;
            border:2px solid white;
        }
        .btn:hover{
            background-color:white;
        }
    </style>
</head>
<body>
    <div class="signupfrm">
        <span class="backbtn">
            <a href="opportUnity.html">←</a>    
        </span>        
        <form method="POST" enctype="multipart/form-data">
            
                <input class="inputs" type="text" name="fname" placeholder="Fullname" id="fname">
            
                <input class="inputs" type="text" name="uname" placeholder="Username" id="uname">
            
                <input class="inputs" type="text" name="pword" placeholder="Password" id="pword">
            
                <!--
                                                    <label for="pword">Password:</label>
                                                    <input type="file" name="pic" ><br/>
                -->

                <div class="gendiv">
                    <div class="gender">
                        <input type="radio" id="m" name="gender" value="Male">
                        <label for="m">Male</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="f" name="gender" value="Female">
                        <label for="f">Female</label>
                    </div>
                </div>
            <div class="btnspc">
                <input class="btn" type="submit" name="submit">
            </div>
        </form>
        
    </div>

</body>
</html>
<?php

//dapat kapag walang laman database di dapat magpupunta sa main page
//hanapin din yung error sa part na di nawawala yung data kapag ni-logout
// tingin ko possible mawala data kapag naghref na, di lang nawawala kapag ni back sa browser
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fname'];
    $username = $_POST['uname'];
    $password = $_POST['pword'];
    //                                      $image = addslashes(file_get_contents($_FILES['pic']['tmp_name']));
    $sex = $_POST['gender'];
    $user_id;

    $conn = new mysqli("localhost", "root", "", "opportunity");

    if ($conn) {
        do {
            $user_id = rand(100000000, 999999999);
            $sql = "SELECT * FROM user WHERE user_ID='$user_id'";
            $result = mysqli_query($conn, $sql);
        } while (mysqli_num_rows($result) > 0);


        $sql = "SELECT * FROM user WHERE user_username='$username' AND user_password = '$password'";
        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if($num > 0)
        {
            echo "<br/>the account is already exist";
        }
        else{
            //$sql = "INSERT INTO user(user_ID, user_fullname, user_username, user_password, profile_photo) VALUES ('$user_id', '$fullname', '$username', '$password', '$image')";
            $sql = "INSERT INTO user(user_ID, user_fullname, user_username, user_password, user_sex) VALUES ('$user_id', '$fullname', '$username', '$password', '$sex')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "User registered successfully!";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }

    } else {
        echo "Connection failed: " . mysqli_connect_error();
    }
}
?>