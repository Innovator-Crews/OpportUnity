<?php
// Start the session
session_start();

// Destroy all session data
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Redirect the user to the login page
header("Location: opportUnity_login.html");  // Replace with your login page URL
exit;
?>