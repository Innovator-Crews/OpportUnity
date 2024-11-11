<?php
session_start();
require 'db_connection.php';  // Include your database connection script

// Check if user is logged in and is an employer
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'employer') {

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input values
        $jobTitle = mysqli_real_escape_string($conn, $_POST['job_title']);
        $companyName = mysqli_real_escape_string($conn, $_POST['company_name']);
        $jobLocation = mysqli_real_escape_string($conn, $_POST['job_location']);
        $jobDescription = mysqli_real_escape_string($conn, $_POST['job_description']);
        $salary = mysqli_real_escape_string($conn, $_POST['salary']);
        $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);
        $qualities = mysqli_real_escape_string($conn, $_POST['qualities']);
        $expectations = mysqli_real_escape_string($conn, $_POST['expectations']);
        $employerId = $_SESSION['user_id'];  // Use the logged-in user's ID as employer_id

        // Check if any field is empty
        if (empty($jobTitle) || empty($companyName) || empty($jobLocation) || empty($jobDescription) || empty($salary) || empty($requirements) || empty($qualities) || empty($expectations)) {
            echo "<script>alert('All fields are required. Please fill in all fields.');</script>";
        } else {
            // Prepare SQL query
            $query = "INSERT INTO job_posts (employer_id, job_title, company_name, job_location, job_description, salary, requirements, qualities, expectations)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issssdsss", $employerId, $jobTitle, $companyName, $jobLocation, $jobDescription, $salary, $requirements, $qualities, $expectations);

            // Execute the query and check if the insertion was successful
            if ($stmt->execute()) {
                echo "<script>alert('Job posted successfully!'); window.location.href='opportUnity_dashboard_employer.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error: " . $stmt->error . "');</script>";
            }
        }
    }

} else {
    // Redirect to login page if not logged in or not an employer
    header("Location: opportUnity_login.php");
    exit;
}
?>
