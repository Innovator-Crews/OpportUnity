<?php
    $user_ID = $_POST['user_ID'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date_of_birth = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $use_phone_number = $_POST['use_phone_number'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $country = $_POST['country'];
    // $pic = $_POST['pic'];
    $role = $_POST['role'];

    if($role == "employee"){
        $school = $_POST['school'];
        $degree = $_POST['degree'];
        $year_graduated = $_POST['year_graduated'];
        $job_title_experience = $_POST['job_title_experience'];
        $company_experience = $_POST['company_experience'];
        $year_of_service_experience = $_POST['year_of_service_experience'];
        $job_description_jobseeker = $_POST['job_description_jobseeker'];
        $jobseeker_skill = $_POST['jobseeker_skill'];
        $jobseeker_certification = $_POST['jobseeker_certification'];
        // $porfolio = $_POST['porfolio'];
        $user_URL = $_POST['user_URL'];
        $job_type = $_POST['job_type'];
        $desired_industry = $_POST['desired_industry'];
        $expected_salary_range = $_POST['expected_salary_range'];
        $willingness_to_relocate = $_POST['willingness_to_relocate'];
        $work_schedule_preference = $_POST['work_schedule_preference'];
        
        $conn = new mysqli("localhost", "root", "", "opportunity");

        // naka newline yang sql syntax every 5variables
        $sql = "UPDATE user SET 
        user_firstname = ?, user_lastname = ?, user_username = ?, user_password = ?, date_of_birth = ?, 
        
        age = ?, use_phone_number = ?, city = ?, province = ?, country = ?, 
        
        school = ?, degree = ?, year_graduated = ?, job_title_experience = ?, company_experience = ?, 
        
        year_of_service_experience = ?, job_description_jobseeker = ?, jobseeker_skill = ?, jobseeker_certification = ?, user_URL = ?, 
        
        job_type = ?, desired_industry = ?, expected_salary_range = ?, willingness_to_relocate = ?, work_schedule_preference = ? 
        
        WHERE user_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssisssssssssisssssssssi", 
        $first_name, $last_name, $email, $password, $date_of_birth, 

        $age, $use_phone_number, $city, $province, $country, 

        $school, $degree, $year_graduated, $job_title_experience, $company_experience,

        $year_of_service_experience, $job_description_jobseeker, $jobseeker_skill, $jobseeker_certification, $user_URL,

        $job_type, $desired_industry, $expected_salary_range, $willingness_to_relocate, $work_schedule_preference, 
        
        $user_ID);
        $stmt->execute();

        header("Location: view_profile.php");
        exit;
          
    }else if($role == "employer"){
        $company_address_city = $_POST['company_address_city'];
        $company_address_province = $_POST['company_address_province'];
        $company_address_country = $_POST['company_address_country'];
        $company_address_URL = $_POST['company_address_URL'];
        $company_industry_type = $_POST['company_industry_type'];
        $company_size = $_POST['company_size'];
        $position_in_company = $_POST['position_in_company'];
        $years_with_company = $_POST['years_with_company'];
        $user_URL = $_POST['user_URL'];
        $preferred_hiring_location = $_POST['preferred_hiring_location'];
        $salary_range = $_POST['salary_range'];

        $conn = new mysqli("localhost", "root", "", "opportunity");
        $sql = "UPDATE user SET 
        user_firstname = ?, user_lastname = ?, user_username = ?, user_password = ?, date_of_birth = ?, 
        
        age = ?, use_phone_number = ?, city = ?, province = ?, country = ?,
        
        company_address_city = ?, company_address_province = ?, company_address_country = ?, company_address_URL = ?, company_industry_type = ?,

        company_size = ?, position_in_company = ?, years_with_company = ?, preferred_hiring_location = ?, salary_range = ?
        
        WHERE user_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssisssssssssissssi", 
        $first_name, $last_name, $email, $password, $date_of_birth, 

        $age, $use_phone_number, $city, $province, $country,

        $company_address_city, $company_address_province, $company_address_country, $company_address_URL, $company_industry_type,

        $company_size, $position_in_company, $years_with_company, $preferred_hiring_location, $salary_range,
    
        $user_ID);
        $stmt->execute();
        
        header("Location: view_profile.php");
        exit;
    }
    
?>






