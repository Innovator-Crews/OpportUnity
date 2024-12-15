<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'] ?? null;
    // 1st form
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $username = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? null;
    // 2nd form
    $date_of_birth = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $use_phone_number = $_POST['use_phone_number'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];

    //$image = addslashes(file_get_contents($_FILES['pic']['tmp_name']));
    if (isset($_FILES['pic']['tmp_name']) && $_FILES['pic']['tmp_name'] !== '') {
        $image = addslashes(file_get_contents($_FILES['pic']['tmp_name']));
    } else {
        $image = null; // Handle this appropriately, e.g., set a default image or throw an error
    }

    // employer & employer form similarities
    $user_URL = $_POST['user_URL'];
 
    // jobseeker form
    // $sex = $_SESSION['sex'] = $_POST['gender'] ?? null;
    $school = $_POST['school'];
    $degree = $_POST['degree'];
    $year_graduated = $_POST['year_graduated'];
    $job_title_experience = $_POST['job_title_experience'];
    $company_experience = $_POST['company_experience'];
    $year_of_service_experience = $_POST['year_of_service_experience'];
    $job_description_jobseeker = $_POST['job_description_jobseeker'];
    $jobseeker_skill = $_POST['jobseeker_skill'];
    $jobseeker_certification = $_POST['jobseeker_certification'];

    //$porfolio = addslashes(file_get_contents($_FILES['porfolio']['tmp_name']));
    if (isset($_FILES['porfolio']['tmp_name']) && is_uploaded_file($_FILES['porfolio']['tmp_name'])) {
        $porfolio = addslashes(file_get_contents($_FILES['porfolio']['tmp_name']));
    } else {
        $porfolio = null; // Set a default value or handle it appropriately
    }

    $job_type = $_POST['job_type'];
    $desired_industry = $_POST['desired_industry'];
    $expected_salary_range = $_POST['expected_salary_range'];
    $willingness_to_relocate = $_POST['willingness_to_relocate'];
    $work_schedule_preference = $_POST['work_schedule_preference'];

    // employer form
    $company_address_city = $_POST['company_address_city'];
    $company_address_province = $_POST['company_address_province'];
    $company_address_country = $_POST['company_address_country'];
    $company_address_URL = $_POST['company_address_URL'];
    $company_industry_type = $_POST['company_industry_type'];
    $company_size = $_POST['company_size'];
    $position_in_company = $_POST['position_in_company'];
    $years_with_company = $_POST['years_with_company'];
    $preferred_hiring_location = $_POST['preferred_hiring_location'];
    $salary_range = $_POST['salary_range'];

    $found = false;

// school, degree, year_graduated, job_title_experience, company_experience, year_of_service_experience, job_description_jobseeker, jobseeker_skill, jobseeker_certification, porfolio, user_URL, job_type, desired_industry, expected_salary_range, willingness_to_relocate, work_schedule_preference, 

// '$school', '$degree', '$year_graduated', '$job_title_experience', '$company_experience', '$year_of_service_experience', '$job_description_jobseeker', '$jobseeker_skill', '$jobseeker_certification', '$porfolio', '$user_URL', '$job_type', '$desired_industry', '$expected_salary_range', '$willingness_to_relocate', '$work_schedule_preference'

// company_address_city, company_address_province, company_address_country, company_address_URL, company_industry_type, company_size, position_in_company, years_with_company, preferred_hiring_location, salary_range,
    
// '$company_address_city', '$company_address_province', '$company_address_country', '$company_address_URL', '$company_industry_type', '$company_size', '$position_in_company', '$years_with_company', '$preferred_hiring_location', '$salary_range'



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
            $_SESSION['error'] = "Account already exist."; 
            header('Location: opportUnity_signup.php'); 
            exit(); 
        }
        else{
            //$sql = "INSERT INTO user(user_ID, user_fullname, user_username, user_password, profile_photo) VALUES ('$user_id', '$fullname', '$username', '$password', '$image')";
            //$sql = "INSERT INTO user(user_ID, user_type, user_firstname, user_lastname, user_username, user_password) VALUES ('$user_id', '$role', '$firstname', '$lastname', '$username', '$password')";
            // for jobseeker
            if($role == 'employee'){
                $sql = "INSERT INTO user(user_ID, user_type, user_firstname, user_lastname, user_username, user_password, user_URL, date_of_birth, age, use_phone_number, city, province, country, school, degree, year_graduated, job_title_experience, company_experience, year_of_service_experience, job_description_jobseeker, jobseeker_skill, jobseeker_certification, porfolio, job_type, desired_industry, expected_salary_range, willingness_to_relocate, work_schedule_preference, profile_photo) VALUES ('$user_id', '$role', '$firstname', '$lastname', '$username', '$password', '$user_URL', '$date_of_birth', '$age', '$use_phone_number', '$city', '$province', '$country', '$school', '$degree', '$year_graduated', '$job_title_experience', '$company_experience', '$year_of_service_experience', '$job_description_jobseeker', '$jobseeker_skill', '$jobseeker_certification', '$porfolio', '$job_type', '$desired_industry', '$expected_salary_range', '$willingness_to_relocate', '$work_schedule_preference', '$image')";
            }
            

            // jor employer
            else if($role == 'employer'){
                $sql = "INSERT INTO user(user_ID, user_type, user_firstname, user_lastname, user_username, user_password, user_URL, date_of_birth, age, use_phone_number, city, province, country, company_address_city, company_address_province, company_address_country, company_address_URL, company_industry_type, company_size, position_in_company, years_with_company, preferred_hiring_location, salary_range, profile_photo) VALUES ('$user_id', '$role', '$firstname', '$lastname', '$username', '$password', '$user_URL', '$date_of_birth', '$age', '$use_phone_number', '$city', '$province', '$country', '$company_address_city', '$company_address_province', '$company_address_country', '$company_address_URL', '$company_industry_type', '$company_size', '$position_in_company', '$years_with_company', '$preferred_hiring_location', '$salary_range', '$image')";
            }
            
            $result = mysqli_query($conn, $sql);
            // para sa admin to
            $sql = "INSERT INTO admin (user_ID, accountState) VALUES ('$user_id', 'active')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                echo "<script>alert('Account created');</script>";
                echo "<script>window.location.replace('opportUnity_login.php');</script>";
                $found = true;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        

    } else {
        echo "Connection failed: " . mysqli_connect_error();
    }
}
?>