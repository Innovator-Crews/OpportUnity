<?php
// db_connection.php

// Replace with your own database credentials
$servername = "localhost";  // Usually 'localhost' for local development
$username = "root";         // Default username for XAMPP
$password = "";             // Default password for XAMPP (empty)
$dbname = "opportunity"; // The name of your database

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
