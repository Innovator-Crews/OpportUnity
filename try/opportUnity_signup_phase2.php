<?php
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $username = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2nd Phase Sign up - OpportUnity</title>

    <link rel="stylesheet" href="opportUnity_signup_phase2.css">

    <link rel="icon" type="image/png" href="faviconlogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
        
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <span class="backbtn">
            <a href="opportUnity_signup.php"><button>Back to Signup</button></a>    
            </span>    

            
            <form action="signup.php" method="POST" enctype="multipart/form-data">

                <!-- Job seeker  -->
                <div class="maincontainer" id="employee">
                    <div class="cell one">
                        <!-- personal details -->
                        <h3>Personal Details</h3>
                        <div class="input-group">
                            <label>Firstname</label>
                            <input type="text" name="first_name" value="<?php echo $firstname; ?>" placeholder="Enter your Firstname" required>
                        </div>

                        <div class="input-group">
                            <label>Lastname</label>
                            <input type="text" name="last_name" value="<?php echo $lastname; ?>" placeholder="Enter your Lastname" required> 
                        </div>

                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" name="email" value="<?php echo $username; ?>" placeholder="Your Username" required>
                        </div>

                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" id="passwordjobseeker" name="password" value="<?php echo $password; ?>" placeholder="Enter your Password" required>
                            <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('passwordjobseeker', this)">Show</button>
                        </div>
                        
                        <div class="input-group">
                            <label>Role</label>
                            <input type="text" name="role" value="<?php echo $role; ?>" placeholder="<?php echo $role; ?>" readonly>
                        </div>


                        <div class="input-group">
                            <label>Date of Birth</label>
                            <input class="inputs" type="text" name="date_of_birth" placeholder="YYYY-MM-DD" >
                        </div>

                        <div class="input-group">
                            <label>Age</label>
                            <input class="inputs" type="text" name="age" placeholder="Ex. 22">
                        </div>

                        <div class="input-group">
                            <label>Phone Number</label>
                            <input class="inputs" type="text" name="use_phone_number" placeholder="09XX-XXX-XXXX">
                        </div>

                        <div class="input-group">
                            <label>City</label>
                            <input class="inputs" type="text" name="city" placeholder="Ex. Balanga">
                        </div>
                        
                        <div class="input-group">
                            <label>Province</label>
                            <input class="inputs" type="text" name="province" placeholder="Ex. Bataan">
                        </div>

                        <div class="input-group">
                            <label>Country</label>
                            <input class="inputs" type="text" name="country" placeholder="Ex. Philippines">
                        </div>

                        <div class="input-group">
                            <label>Profile Picture (jpg)</label>
                            <input type="file" name="pic" accept="image/jpeg">
                        </div>
                    </div>

                    <div class="cell two">
                        <!-- educational background -->
                        <h3>Educational Background</h3>
                        <div class="input-group">
                            <label>School Name</label>
                            <input class="inputs" type="text" name="school" placeholder="Enter your School Name">
                        </div>
                        <div class="input-group">
                            <label>Degree</label>
                            <input class="inputs" type="text" name="degree" placeholder="Enter your Degree">
                        </div>
                        <div class="input-group">
                            <label>Year Graduated</label>
                            <input class="inputs" type="text" name="year_graduated" placeholder="YYYY-MM-DD ">
                        </div>
                    </div>

                    <div class="cell three">
                        <!-- work experience -->
                        <h3>Work Experience</h3>
                        <div class="input-group">
                            <label>Job Title</label>
                        <input class="inputs" type="text" name="job_title_experience" placeholder="Enter your Job Title ">
                        </div>
                        <div class="input-group">
                            <label>Company Name</label>
                            <input class="inputs" type="text" name="company_experience" placeholder="Enter your Company Name ">
                        </div>
                        <div class="input-group">
                            <label>Years of Service</label>
                            <input class="inputs" type="text" name="year_of_service_experience" placeholder="YYYY-MM-DD ">
                        </div>
                        <div class="input-group">
                            <label>Describe your previous work</label>
                            <input class="inputs" type="text" name="job_description_jobseeker" placeholder="Type your Experience "> 
                        </div>
                    </div>

                    <div class="cell three">
                        <!-- skills and certification -->
                        <h3>Skills and Certification</h3>
                        <div class="input-group">
                            <label>List your skills</label>
                            <input class="inputs" type="text" name="jobseeker_skill" placeholder="Enter your Skills and Certification ">
                        </div>
                        <div class="input-group">
                            <label>List your certificates</label>
                            <input class="inputs" type="text" name="jobseeker_certification" placeholder="Title of Certificates ">
                        </div>
                    
                        <!-- portfolio/resume -->
                        <div class="input-group">
                            <label>Upload you resume</label>
                            <input type="file" name="porfolio" placeholder="accepted image(.jpg) ">
                        </div>

                        <!-- profile url -->
                        <div class="input-group">
                            <label>Paste your URL profile link</label>
                            <input class="inputs" type="text" name="user_URL" placeholder="Upload Profile Link "> 
                        </div>
                    </div>

                    <div class="cell four">
                        <!-- preference -->
                        <h3>Preference</h3>
                        <div class="input-group">
                            <label>Job Type</label>
                            <input class="inputs" type="text" name="job_type" placeholder="(Full-time/Part-time/freelance) ">

                        </div>
                        <div class="input-group">
                            <label>Desired Industry</label>
                            <input class="inputs" type="text" name="desired_industry" placeholder="Type your Industry ">

                        </div>
                        <div class="input-group">
                            <label>Expected Salary Range</label>
                            <input class="inputs" type="text" name="expected_salary_range" placeholder="Ex. 20,000 - 30,000  ">
                        </div>
                        <div class="input-group">
                            <label>Willingness to Relocate</label>
                            <input class="inputs" type="text" name="willingness_to_relocate" placeholder="(Yes/No)"> 
                        </div>
                        <div class="input-group">
                            <label>Work Schedule Preference</label>
                            <input class="inputs" type="text" name="work_schedule_preference" placeholder="(Day/Night/Graveyard)">
                        </div>
                    </div>
                </div>

                <!-- employer -->
                <div class="maincontainer" id="employer">
                    <div class="cell one">
                        <!-- personal details -->
                        <h3>Personal Details</h3>
                        <div class="input-group">
                            <label>Firstname</label>
                            <input type="text" name="first_name" value="<?php echo $firstname; ?>" placeholder="<?php echo $firstname; ?>">
                        </div>

                        <div class="input-group">
                            <label>Lastname</label>
                            <input type="text" name="last_name" value="<?php echo $lastname; ?>" placeholder="<?php echo $lastname; ?>">
                        </div>

                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" name="email" value="<?php echo $username; ?>" placeholder="<?php echo $username; ?>">
                        </div>

                        <div class="input-group">
                            <label>Password</label>
                            <input type="password" id="passwordemployer" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $password; ?>">
                            <button type="button" class="show-password-btn" onclick="togglePasswordVisibility('passwordemployer', this)">Show</button>
                        </div>
                        
                        <div class="input-group">
                            <label>Role</label>
                            <input type="text" name="role" value="<?php echo $role; ?>" placeholder="<?php echo $role; ?>">
                        </div>


                        <div class="input-group">
                            <label>Date of Birth</label>
                            <input class="inputs" type="text" name="date_of_birth" placeholder="YYYY-MM-DD ">
                        </div>

                        <div class="input-group">
                            <label>Age</label>
                            <input class="inputs" type="text" name="age" placeholder="Ex.20 ">
                        </div>

                        <div class="input-group">
                            <label>Phone Number</label>
                            <input class="inputs" type="text" name="use_phone_number" placeholder="09XX-XXX-XXXX ">

                        </div>

                        <div class="input-group">
                            <label>City</label>
                            <input class="inputs" type="text" name="city" placeholder="Ex. Balanga ">
                        </div>
                        
                        <div class="input-group">
                            <label>Province</label>
                            <input class="inputs" type="text" name="province" placeholder="Ex. Bataan ">
                        </div>

                        <div class="input-group">
                            <label>Country</label>
                            <input class="inputs" type="text" name="country" placeholder="Ex. Philippines ">
                        </div>

                        <div class="input-group">
                            <label>Profile Picture (jpg)</label>
                            <input type="file" name="pic">
                        </div>
                    </div>

                    <div class="cell two">
                        <!-- company details -->
                        <h3>Company Details</h3>
                        <div class="input-group">
                            <label>City</label>
                            <input class="inputs" type="text" name="company_address_city" placeholder="Enter Company Details ">
                        </div>
                        <div class="input-group">
                            <label>Province</label>
                            <input class="inputs" type="text" name="company_address_province" placeholder="Ex. Bataan ">
                        </div>
                        <div class="input-group">
                            <label>Country</label>
                            <input class="inputs" type="text" name="company_address_country" placeholder="Ex. Philippines ">
                        </div>
                        <div class="input-group">
                            <label>Company website URL</label>
                            <input class="inputs" type="text" name="company_address_URL" placeholder="Upload Company Website Link "> 
                        </div>
                        <div class="input-group">
                            <label>Aimed Industry</label>
                            <input class="inputs" type="text" name="company_industry_type" placeholder="Enter your Industry ">

                        </div>
                        <div class="input-group">
                            <label>Company Size</label>
                            <input class="inputs" type="text" name="company_size" placeholder="Ex. 890 ">
                        </div>
                    </div>

                    <div class="cell three">
                        <!-- Professional Details -->
                        <h3>Professional Details</h3>
                        <div class="input-group">
                            <label>Position in the Company</label>
                            <input class="inputs" type="text" name="position_in_company" placeholder="Ex. CEO">
                        </div>
                        <div class="input-group">
                            <label>Years of service</label>
                            <input class="inputs" type="text" name="years_with_company" placeholder="YYYY-MM-DD  ">
                        </div>
                        <div class="input-group">
                            <label>Upload Profile URL</label>
                            <input class="inputs" type="text" name="user_URL" placeholder="Upload Profile Link "> 
                        </div>
                    </div>

                    <div class="cell four">
                        <!-- Preference for Job Postings -->
                        <h3>Preference for Job Postings</h3>
                        <div class="input-group">
                            <label>Preferred Hiring Location</label>
                            <input class="inputs" type="text" name="preferred_hiring_location" placeholder="(On-site/Online/Hybrid) ">
                        </div>
                        <div class="input-group">
                            <label>Salary Range</label>
                            <input class="inputs" type="text" name="salary_range" placeholder="Ex. 50,000 - 100,000  ">
                        </div>
                    </div>
                </div>
            
                <input class="btn" type="submit" name="submit">

            </form>
        </div>
    </div>

    <script>
        // Role passed from PHP
        var usertype = '<?=$role?>';

        console.log(usertype)

        // Sections for jobseeker and employer
        var employee = document.getElementById('employee');
        var employer = document.getElementById('employer');

        // Hide both by default
        employee.style.display = 'none';
        employer.style.display = 'none';

        employee.classList.remove('employee');
        employer.classList.remove('employer');

        // Show the appropriate section based on the role
        if (usertype === 'employee') {
            employee.style.display = 'grid';
            employee.style.gridTemplateColumns = 'repeat(2, 1fr)';
            employee.style.gap = '15px';
            employee.style.width = '100%';
            employee.style.border = '';
            employee.style.boxShadow = '';
            
        } else if (usertype === 'employer') {
            employer.style.display = 'grid';
            employer.style.gridTemplateColumns = 'repeat(2, 1fr)';
            employer.style.gap = '30px';
            employer.style.width = '100%';
            employer.style.border = '';
            employer.style.boxShadow = '';
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            
            if (input.type === "password") {
                input.type = "text";
                button.textContent = "Hide";
            } else {
                input.type === "password";
                button.textContent = "Show";
            }
        }

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