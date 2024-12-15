
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - OpportUnity</title>

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
            <p>Grab Opportunity with us!</p>
            <h2>Create Account</h2>

            <div class="login-link">
                <p>Already have an account? <a href="opportUnity_login.php">Login</a></p>
            </div>

            <!-- Form -->
            <form action="opportUnity_signup_phase2.php" method="POST">

                <div class="user-role">
                    <p>Select your role:</p>
                        <div class="radio-option">
                            <input type="radio" name="role" id="employee" value="employee" required>
                            <label for="employee">Job Seeker</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="role" id="employer" value="employer" required>
                            <label for="employer">Employer</label>
                        </div>
                </div>

                <div class="name-fields">
                    <div class="input-group">
                        <input type="text" name="first_name" id="first_name" placeholder=" "  required >
                        <label for="first_name">First name</label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="last_name" id="last_name" placeholder=" "  required>
                        <label for="last_name">Last name</label>
                    </div>
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder=" " required>
                    <label for="email">Email</label>
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder=" " required>
                    <label for="password">Password</label>
                    <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('password', this)">Show</button>
                </div>

                <div class="input-group">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                    <label for="confirm_password">Confirm Password</label>
                    <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('confirm_password', this)">Show</button>
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirm_password");

            form.addEventListener("submit", function(event) {
                // Check if passwords match
                if (password.value !== confirmPassword.value) {
                    event.preventDefault(); // Prevent form submission
                    alert("Passwords do not match. Please try again.");
                }
            });
        });

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

