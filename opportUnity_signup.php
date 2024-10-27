<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <link rel="stylesheet" href="opportUnity_signup.css">

    <link rel="icon" type="image/png" href="faviconlogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
    
</head>
<body>
    <!-- Nav bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div class="logo">OpportUnity</div>
            <ul class="nav-links">
                <li><a href="#">Landing Page</a></li>
                <li><a href="#">Terms & Condition</a></li>
            </ul>
        </div>
    </nav>

    <!-- Starting -->
    <div class="container">
        <div class="signup-container">
            <p>Grab Opportunity with us!</p>
            <h2>Create Account</h2>

            <div class="login-link">
                <p>Already have an account? <a href="opportUnity_login.php">Login</a></p>
            </div>

            <!-- Form -->
            <form method="POST">
                <div class="name-fields">
                    <div class="input-group">
                        <input type="text" name="first_name" id="first_name" placeholder=" " required>
                        <label class="input-box" for="first_name">First name</label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="last_name" id="last_name" placeholder=" " required>
                        <label class="input-box" for="last_name">Last name</label>
                    </div>
                </div>

                <div class="name-fields">
                    <div>
                        <input type="radio" id="employer">
                        <label for="employer">Employer</label>
                    </div>
                    <div>
                        <input type="radio" id="employee">
                        <label for="employee">Employee</label>
                    </div>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required>
                    <label class="input-box" for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label class="input-box" for="password">Password</label>
                </div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label class="input-box" for="confirm_password">Confirm Password</label>
                </div>

                <!-- Finale -->
                 <div class="terms">
                    <p>
                        By creating an account, you agree to our <br><a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.
                    </p>
                 </div>
                
                <button type="submit" class="btn">Create Account</button>
            </form>
        </div>
    </div>

</body>
</html>
<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $username = $_POST['email'];
    $password = $_POST['password'];
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
            //$sql = "INSERT INTO user(user_ID, user_fullname, user_username, user_password, user_sex) VALUES ('$user_id', '$fullname', '$username', '$password', '$sex')";
            $sql = "INSERT INTO user(user_ID, user_firstname, user_lastname, user_username, user_password) VALUES ('$user_id', '$firstname', '$lastname', '$username', '$password')";
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