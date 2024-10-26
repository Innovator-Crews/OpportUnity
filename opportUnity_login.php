<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Account</title>

    <link rel="stylesheet" href="opportUnity_login.css">

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
            <p>Welcome, OpportUnity is waiting!</p>
            <h2>Access your Account</h2>

            <div class="login-link">
                <p>No account yet? <a href="opportUnity_signup.html">Register now</a></p>
            </div>

            <!-- Form -->
            <form action="http://localhost/trylangpangwebhost/opportUnity_mainpage.php" id="myForm" method="POST">
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>

                <!-- Finale -->
                 <div class="terms">
                    <p>
                        Error text sample.
                        <br><a href="#">Forgot Password?</a>.
                    </p>
                 </div>
                
                <button type="button" class="btn" onclick="submitForm()">Sign in</button>
            </form>
        </div>
    </div>


</body>
</html>
<?php
$conn = new mysqli("localhost", "root", "", "opportunity");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

$u_s_e_r = [[], []];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $u_s_e_r[0][] = $row['user_username'];
        $u_s_e_r[1][] = $row['user_password'];
    }
}

// Convert PHP array to JSON
$u_s_e_r_json = json_encode($u_s_e_r);
?>


<script>
        // Pass the PHP array to JavaScript
        var userArray = <?php echo $u_s_e_r_json; ?>;

        function submitForm() {
            let usernameMatched = false;
            let passwordMatched = false;

            const type_uname = document.getElementById('email').value;
            userArray[0].forEach(function(usernm) {
                if (usernm === type_uname) {
                    usernameMatched = true;
                }
            });

            const type_pword = document.getElementById('password').value;
            userArray[1].forEach(function(userps) {
                if (userps === type_pword) {
                    passwordMatched = true;
                }
            });

            if (usernameMatched && passwordMatched) {
                // Submit the form
                document.getElementById('myForm').submit();
            } else {
                document.getElementById('invalid').innerHTML = "Invalid username or password";
            }
        }
    </script>