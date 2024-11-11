<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Account - OpportUnity</title>
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
            <a href="opportUnity.html"><img src="logo.png" alt="OpportUnity Logo" class="navbar-logo"></a>
            <a href="opportUnity.html"><div class="logo">OpportUnity</div></a>
            <ul class="nav-links">
                <li><a href="opportUnity.html">Landing Page</a></li>
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
            <form method="POST" id="myForm" action="">
            <input type="hidden" id="userID" name="userID">
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('password', this)">Show</button>
                </div>
            </form><button class="btn" type="button" onclick="submitForm()">LOGIN</button>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            
            if (input.type === "password") {
                input.type = "text";
                button.textContent = "Hide";
            } else {
                input.type = "password";
                button.textContent = "Show";
            }
        }
    </script>
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
?><?php
$conn = new mysqli("localhost", "root", "", "opportunity");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

$u_s_e_r = [[], [], [], []];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $u_s_e_r[0][] = $row['user_username'];
        $u_s_e_r[1][] = $row['user_password'];
        $u_s_e_r[2][] = $row['user_type'];
        $u_s_e_r[3][] = $row['user_ID'];
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
    let matchedIndex = -1;

    const type_email = document.getElementById('email').value;
    userArray[0].forEach(function(usernm, index) {
        if (usernm === type_email) {
            usernameMatched = true;
            matchedIndex = index;
        }
    });

    const type_password = document.getElementById('password').value;
    if (matchedIndex !== -1 && userArray[1][matchedIndex] === type_password) {
        passwordMatched = true;
    }

    if (usernameMatched && passwordMatched) {
        var jobtype = userArray[2][matchedIndex];
        var uid = userArray[3][matchedIndex];

        // Set the value of the hidden input field
        document.getElementById('userID').value = uid; // Example value

        if (jobtype == "employer") {
            //dito punta kapag employer naglogin
            document.getElementById('myForm').action = "opportUnity_dashboard_employer.php";
        } else {
            //dito kapag jobseeker
            document.getElementById('myForm').action = "opportUnity_dashboard_jobseeker.php";
        }
        // Submit the form
        document.getElementById('myForm').submit();
    } else {
        document.getElementById('invalid').innerHTML = "Invalid username or password";
    }
}

</script>