<?php
    session_start();
    $_SESSION['role'] = 'employee';

    // Global variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_username='$userNAME' AND user_password='$userPASSWORD'";
    $result = mysqli_query($conn, $sql);

    $pic_identifier = '';
    // purpose nito is kahit na ivisit ng user yung ibang user is mananatili padin yung notif nya
    if ($result && ($userNAME != null && $userPASSWORD != null)) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) 
        {
            $user = mysqli_fetch_assoc($result);
            $first = $user['user_firstname'];
            $last = $user['user_lastname'];
            $name = $first . " " . $last;
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);
            $user_notification = $user['user_notification'];
            $user_ID = $user['user_ID'];
        }
    }


    $role = $_POST['role'];
    $user_ID = $_POST['user_ID'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_ID='$user_ID' AND user_type='$role'";
    $result = mysqli_query($conn, $sql);

    $pic_identifier = '';

    // Redirect to login page if not logged in
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $user_firstname = $user['user_firstname'];
            $user_lastname = $user['user_lastname'];
            $user_username = $user['user_username'];
            $user_password = $user['user_password'];
            $name = $user_firstname . " " . $user_lastname;
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);

            // 2nd form
            $date_of_birth = $user['date_of_birth'];
            $age = $user['age'];
            $use_phone_number = $user['use_phone_number'];
            $city = $user['city'];
            $province = $user['province'];
            $country = $user['country'];
            $user_URL = $user['user_URL'];


            // jobseeker form
            if($role == 'employee'){
                // $sex = $_SESSION['sex'] = $user['gender'] ?? null;
                $school = $user['school'];
                $degree = $user['degree'];
                $year_graduated = $user['year_graduated'];
                $job_title_experience = $user['job_title_experience'];
                $company_experience = $user['company_experience'];
                $year_of_service_experience = $user['year_of_service_experience'];
                $job_description_jobseeker = $user['job_description_jobseeker'];
                $jobseeker_skill = $user['jobseeker_skill'];
                $jobseeker_certification = $user['jobseeker_certification'];
                $porfolio = "data:image/jpeg;base64," . base64_encode($user['porfolio']);
                $job_type = $user['job_type'];
                $desired_industry = $user['desired_industry'];
                $expected_salary_range = $user['expected_salary_range'];
                $willingness_to_relocate = $user['willingness_to_relocate'];
                $work_schedule_preference = $user['work_schedule_preference'];
            }
            else if($role == 'employer'){
                $company_address_city = $user['company_address_city'];
                $company_address_province = $user['company_address_province'];
                $company_address_country = $user['company_address_country'];
                $company_address_URL = $user['company_address_URL'];
                $company_industry_type = $user['company_industry_type'];
                $company_size = $user['company_size'];
                $position_in_company = $user['position_in_company'];
                $years_with_company = $user['years_with_company'];
                $preferred_hiring_location = $user['preferred_hiring_location'];
                $salary_range = $user['salary_range'];
            }
        }
    }

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
    <title>Edit Profile - OpportUnity</title>

    <link rel="stylesheet" href="opportUnity_edit_profile.css">

    <link rel="icon" type="image/png" href="faviconlogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
    
</head>
<body>
   <!-- Nav bar -->
   <nav class="navbar">
        <!-- <div class="navbar-left">
            <img onclick="logout()" src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div onclick="logout()" class="logo">OpportUnity</div>
            <ul class="nav-links">
                <li><a href="opportUnity_dashboard_employer.php">Homepage</a></li>
                <li><a href="opportUnity_postjob.php">Post a Job</a></li>
                <li><a href="opportUnity_joblist_employer.php">Your Jobs</a></li>
                <li><a href="opportUnity_applicantslist_employer.php">Applicants List</a></li>
            </ul>
        </div> -->

        <div class="navbar-left">
            <img onclick="logout()" src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div onclick="logout()" class="logo">OpportUnity</div>
            <ul class="nav-links">
                <?php if ($role === 'employer'): ?>
                    <li><a href="opportUnity_dashboard_employer.php">Homepage</a></li>
                    <li><a href="opportUnity_postjob.php">Post a Job</a></li>
                    <li><a href="opportUnity_joblist_employer.php">Your Jobs</a></li>
                    <li><a href="opportUnity_applicantslist_employer.php">Applicants List</a></li>
                <?php elseif ($role === 'employee'): ?>
                    <li><a href="opportUnity_dashboard_jobseeker.php">Homepage</a></li>
                    <li><a href="search_page.php">Search Job</a></li>
                    <li><a href="opportUnity_all_job_posted.php">Vacant Jobs</a></li>
                    <li><a href="opportUnity_joblist_jobseeker.php">Applied List</a></li>
                <?php else: ?>
                    <!-- Redirect to login page or show a generic navbar if role is not set -->
                    <li><a href="opportUnity_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="navbar-right">
            <div id="userMenu" class="user-menu">
                <span id="userName" onclick="toggleDropdown()">Welcome, <?=$name?>!</span>
                <div id="dropdown" class="dropdown-content">
                    <a href="view_profile.php">View Profile</a>
                    <a href="#" onclick="logout()">Logout</a>
                </div>
            </div>
            <a href="opportUnity_notification_list.php"><div id="user_notif" onclick="" style="width:40px; height:40px; border-radius:100%; background-size:cover; margin:0px 20px 0px 30px;"></div></a>
            <?php if(!($pic_identifier == null)){?>
                <a href="view_profile.php"><img href="view_profile.php" src="<?=$pic?>" alt="" width="100px" height="100px"></a>
            <?php }else{?>
                <a href="view_profile.php"><img href="view_profile.php" src="default_profile.jpg" alt="" width="100px" height="100px"></a>
            <?php }?>
            
        </div>
    </nav>


    <div class="container">
        <div class="signup-container">
            <span class="backbtn">
            <a href="view_profile.php"><button>Back to Profile</button></a>    
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
                            <input class="inputs" type="text" name="date_of_birth" placeholder="YYYY-MM-DD" required accept="">
                        </div>

                        <div class="input-group">
                            <label>Age</label>
                            <input class="inputs" type="text" name="age" placeholder="Ex. 22" required>
                        </div>

                        <div class="input-group">
                            <label>Phone Number</label>
                            <input class="inputs" type="text" name="use_phone_number" placeholder="09XX-XXX-XXXX" required>
                        </div>

                        <div class="input-group">
                            <label>City</label>
                            <input class="inputs" type="text" name="city" placeholder="Ex. Balanga" required>
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
                            <input class="inputs" type="text" name="year_graduated" placeholder="YYYY-MM-DD">
                        </div>
                    </div>

                    <div class="cell three">
                        <!-- work experience -->
                        <h3>Work Experience</h3>
                        <div class="input-group">
                            <label>Job Title</label>
                        <input class="inputs" type="text" name="job_title_experience" placeholder="Enter your Job Title">
                        </div>
                        <div class="input-group">
                            <label>Company Name</label>
                            <input class="inputs" type="text" name="company_experience" placeholder="Enter your Company Name">
                        </div>
                        <div class="input-group">
                            <label>Years of Service</label>
                            <input class="inputs" type="text" name="year_of_service_experience" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="input-group">
                            <label>Describe your previous work</label>
                            <input class="inputs" type="text" name="job_description_jobseeker" placeholder="Type your Experience"> 
                        </div>
                    </div>

                    <div class="cell three">
                        <!-- skills and certification -->
                        <h3>Skills and Certification</h3>
                        <div class="input-group">
                            <label>List your skills</label>
                            <input class="inputs" type="text" name="jobseeker_skill" placeholder="Enter your Skills and Certification">
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
                            <input class="inputs" type="text" name="expected_salary_range" placeholder="Ex. 20,000 - 30,000 ">
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
                            <input class="inputs" type="text" name="date_of_birth" placeholder="YYYY-MM-DD">
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
                            <input class="inputs" type="text" name="years_with_company" placeholder="YYYY-MM-DD ">
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
                            <input class="inputs" type="text" name="salary_range" placeholder="Ex. 50,000 - 100,000 ">
                        </div>
                    </div>
                </div>
            
                <input class="btn" type="submit" name="submit">

            </form>
        </div>
    </div>

    <!-- dito yung nasa notes -->

    <script>

        // Role passed from PHP
        var usertype = '<?=$role?>';

        console.log(usertype);

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

        var user_notification = "<?=$user_notification?>";
        var unotif = document.getElementById('user_notif');

        function user_notification_icon() {
            if (user_notification == 1) {
                unotif.style.backgroundImage = "url('red_notif.jpg')";
            } else {
                // Add your else condition here
                unotif.style.backgroundImage = "url('default_notif.jpg')"; // Example default image
            }
        }

        // Call the function to set the initial icon 
        user_notification_icon();

        // Toggle the dropdown visibility
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdown");
            const isDropdownVisible = dropdown.classList.toggle("show");
            
            // Set the dropdown's display based on visibility
            dropdown.style.display = isDropdownVisible ? "block" : "none";

            // Add or remove the outside click listener
            if (isDropdownVisible) {
                document.addEventListener("click", closeDropdownOnClickOutside);
            } else {
                document.removeEventListener("click", closeDropdownOnClickOutside);
            }
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

        // Toggle the dropdown visibility
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdown");
            const isDropdownVisible = dropdown.classList.toggle("show");
            
            // Set the dropdown's display based on visibility
            dropdown.style.display = isDropdownVisible ? "block" : "none";

            // Add or remove the outside click listener
            if (isDropdownVisible) {
                document.addEventListener("click", closeDropdownOnClickOutside);
            } else {
                document.removeEventListener("click", closeDropdownOnClickOutside);
            }
        }

        // Close dropdown when clicking outside
        function closeDropdownOnClickOutside(event) {
            const dropdown = document.getElementById("dropdown");
            const userName = document.getElementById("userName");

            // Debug logs
            console.log("Dropdown:", dropdown);
            console.log("Event Target:", event.target);
            console.log("Is click inside dropdown?", dropdown.contains(event.target));
            console.log("Is click on username?", userName.contains(event.target));

            // Check if click is outside both dropdown and userName
            if (!dropdown.contains(event.target) && !userName.contains(event.target)) {
                dropdown.classList.remove("show");
                dropdown.style.display = "none"; // Hide dropdown
                document.removeEventListener("click", closeDropdownOnClickOutside);
            }
        }

        function logout() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'logout.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert(xhr.responseText);
                    window.location.href = 'opportUnity_login.php';
                }
            };
            xhr.send();
        }

    </script>

</body>
</html>

