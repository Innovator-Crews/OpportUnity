<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Default password for XAMPP's MySQL is empty
$dbname = "opportunity"; // Name of your database

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Capture form data
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password']; // Note: Plain text storage for demonstration purposes
$role = $_POST['role'];

// Check if email is already registered
$sqlCheckEmail = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sqlCheckEmail);

if ($result->num_rows > 0) {
    echo "Email already registered. Please try a different email.";
} else {
    // SQL to insert user data
    $sql = "INSERT INTO users (first_name, last_name, email, password, role)
            VALUES ('$firstName', '$lastName', '$email', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
        header("Location: opportUnity_login.html"); // Redirect to login page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>