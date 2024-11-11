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

// Debugging - output the $_POST array to confirm the data is being sent
echo '<pre>';
print_r($_POST); // Check the POST data
echo '</pre>';

// Capture form data with validation
if (isset($_POST['email'])) {
    $email = $_POST['email'];
} else {
    echo "Email not provided.";
    exit;
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
} else {
    echo "Password not provided.";
    exit;
}

// SQL to fetch user data based on email
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

session_start();

if ($result->num_rows > 0) {
    // User exists, fetch user details
    $user = $result->fetch_assoc();

    // Debugging - print both the entered and stored passwords for comparison
    echo "Entered password: " . $password . "<br>";
    echo "Stored password: " . $user['PASSWORD'] . "<br>";

    // Check if the entered password matches the stored password (plain text comparison)
    if ($user['PASSWORD'] === $password) {
        // Password matches, login successful

        // Set session variables
        $_SESSION['user_id'] = $user['id'];  // Set user ID
        $_SESSION['first_name'] = $user['first_name'];  // Set user's first name
        $_SESSION['email'] = $user['email'];  // Set user's email
        $_SESSION['role'] = $user['role'];  // Set user's role (employee/employer)

        // Redirect to the appropriate dashboard based on the user's role
        if ($user['role'] === 'employer') {
            header("Location: opportUnity_dashboard_employer.php");
        } else if ($user['role'] === 'employee') {
            header("Location: opportUnity_dashboard_jobseeker.php");
        } else {
            echo "Invalid role.";
        }
        exit;
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
