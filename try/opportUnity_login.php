<?php 
session_unset();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to Account - OpportUnity</title>

    <link rel="stylesheet" href="opportUnity_login2.css">

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

    <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<script>alert("' . $_SESSION['error'] . '");</script>';
            unset($_SESSION['error']);
        }
    ?>

    <!-- Starting -->
    <div class="container">
        <div class="signup-container">
            <p>Welcome, OpportUnity is waiting!</p>
            <h2>Access your Account</h2>

            <div class="login-link">
                <p>No account yet? <a href="opportUnity_signup.php">Register now</a></p>
            </div>


            <!-- Form -->
            <form method="POST" id="myForm" action="login.php">
            <input type="hidden" id="userID" name="userID">
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required >
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required >
                    <label for="password">Password</label>
                    <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('password', this)">Show</button>
                </div><button class="btn" type="submit">LOGIN</button>
            </form>
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
        document.getElementById('email').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the default form submission
                document.getElementById('myForm').submit(); // Submit the form
            }
        });

        document.getElementById('password').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the default form submission
                document.getElementById('myForm').submit(); // Submit the form
            }
        });
    </script>
</body>
</html>