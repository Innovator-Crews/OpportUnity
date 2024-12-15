<?php
// Start the session
session_start();
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
            $_SESSION['usertype'] = $role;
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);
            $user_notification = $user['user_notification'];
            $user_ID = $user['user_ID'];
        }
    }

// ito ay kapag yung employer nagview ng profile ng user

// kapag niclick ng user yung ibang user profile, ito yung gagana
// kapag nag flase tong if statement na to, ibigsabihin niclick ng user sarili nyang profile
if (isset($_POST['different_user_type']) && isset($_POST['different_user_userid']) && $_POST['different_user_type'] == "employee") 
{ 
    // ito ay para ihide yung edit button kapag nagview ng profile ng iba yung user
    $edit = false;

    // it ay para makita sa navigationbar yung profile ng user(hindi user na ivivisit)
    // Retrieve session variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;

    // Database connection
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_username='$userNAME' AND user_password='$userPASSWORD'";
    $result = mysqli_query($conn, $sql);

    $pic_identifier = '';

    // Redirect to login page if not logged in
    if ($result && ($userNAME != null && $userPASSWORD != null)) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $first = $user['user_firstname'];
            $last = $user['user_lastname'];
            $name = $first . " " . $last;
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);
        }
    }

    //eto naman yung para sa user na vivisit
    $different_user_type = $_POST['different_user_type']; 
    $different_user_userid = $_POST['different_user_userid'];

    // $different_user_userid at $different_user_role ay parehas lang

    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_ID='$different_user_userid' AND user_type='$different_user_type'";
    $result = mysqli_query($conn, $sql);

    $different_user_pic_identifier = '';
    
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $different_user_user = mysqli_fetch_assoc($result);
            $different_user_first = $user['user_firstname'];
            $different_user_last = $user['user_lastname'];
            $different_user_email = $user['user_username'];
            $different_user_name = $different_user_first . " " . $different_user_last;
            $different_user_pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $different_user_role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $different_user_pic_identifier = base64_encode($user['profile_photo']);

            // 2nd form
            $different_user_date_of_birth = $user['date_of_birth'];
            $different_user_age = $user['age'];
            $different_user_use_phone_number = $user['use_phone_number'];
            $different_user_city = $user['city'];
            $different_user_province = $user['province'];
            $different_user_country = $user['country'];
            $different_user_user_URL = $user['user_URL'];

            // jobseeker form
            if($different_user_role == 'employee'){
                // $sex = $_SESSION['sex'] = $user['gender'] ?? null;
                $different_user_school = $user['school'];
                $different_user_degree = $user['degree'];
                $different_user_year_graduated = $user['year_graduated'];
                $different_user_job_title_experience = $user['job_title_experience'];
                $different_user_company_experience = $user['company_experience'];
                $different_user_year_of_service_experience = $user['year_of_service_experience'];
                $different_user_job_description_jobseeker = $user['job_description_jobseeker'];
                $different_user_jobseeker_skill = $user['jobseeker_skill'];
                $different_user_jobseeker_certification = $user['jobseeker_certification'];
                $different_user_porfolio = "data:image/jpeg;base64," . base64_encode($user['porfolio']);
                $different_user_job_type = $user['job_type'];
                $different_user_desired_industry = $user['desired_industry'];
                $different_user_expected_salary_range = $user['expected_salary_range'];
                $different_user_willingness_to_relocate = $user['willingness_to_relocate'];
                $different_user_work_schedule_preference = $user['work_schedule_preference'];
            }
            else if($different_user_role == 'employer'){
                $different_user_company_address_city = $user['company_address_city'];
                $different_user_company_address_province = $user['company_address_province'];
                $different_user_company_address_country = $user['company_address_country'];
                $different_user_company_address_URL = $user['company_address_URL'];
                $different_user_company_industry_type = $user['company_industry_type'];
                $different_user_company_size = $user['company_size'];
                $different_user_position_in_company = $user['position_in_company'];
                $different_user_years_with_company = $user['years_with_company'];
                $different_user_preferred_hiring_location = $user['preferred_hiring_location'];
                $different_user_salary_range = $user['salary_range'];
            }
        }
    } else {
        header('Location: opportUnity_login.php');
        exit();
    }
    // kapag employer nagvisit ng jobseeker eto gagana
}else if (isset($_POST['different_user_type']) && isset($_POST['different_user_userid']) && $_POST['different_user_type'] == "employer") 
{ 
    // ito ay para ihide yung edit button kapag nagview ng profile ng iba yung user
    $edit = false;

    // it ay para makita sa navigationbar yung profile ng user(hindi user na ivivisit)
    // Retrieve session variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;

    // Database connection
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_username='$userNAME' AND user_password='$userPASSWORD'";
    $result = mysqli_query($conn, $sql);

    $pic_identifier = '';

    // Redirect to login page if not logged in
    if ($result && ($userNAME != null && $userPASSWORD != null)) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $first = $user['user_firstname'];
            $last = $user['user_lastname'];
            $name = $first . " " . $last;
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);
        }
    }

    //eto naman yung para sa user na vivisit
    $different_user_type = $_POST['different_user_type']; 
    $different_user_userid = $_POST['different_user_userid'];

    // $different_user_userid at $different_user_role ay parehas lang

    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_ID='$different_user_userid' AND user_type='$different_user_type'";
    $result = mysqli_query($conn, $sql);

    $different_user_pic_identifier = '';
    
    if ($result) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $different_user_user = mysqli_fetch_assoc($result);
            $different_user_first = $user['user_firstname'];
            $different_user_last = $user['user_lastname'];
            $different_user_username = $user['user_username'];
            $different_user_name = $different_user_first . " " . $different_user_last;
            $different_user_pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $different_user_role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $different_user_pic_identifier = base64_encode($user['profile_photo']);

            // 2nd form
            $different_user_date_of_birth = $user['date_of_birth'];
            $different_user_age = $user['age'];
            $different_user_use_phone_number = $user['use_phone_number'];
            $different_user_city = $user['city'];
            $different_user_province = $user['province'];
            $different_user_country = $user['country'];
            $different_user_user_URL = $user['user_URL'];

            // jobseeker form
            if($different_user_role == 'employee'){
                // $sex = $_SESSION['sex'] = $user['gender'] ?? null;
                $different_user_school = $user['school'];
                $different_user_degree = $user['degree'];
                $different_user_year_graduated = $user['year_graduated'];
                $different_user_job_title_experience = $user['job_title_experience'];
                $different_user_company_experience = $user['company_experience'];
                $different_user_year_of_service_experience = $user['year_of_service_experience'];
                $different_user_job_description_jobseeker = $user['job_description_jobseeker'];
                $different_user_jobseeker_skill = $user['jobseeker_skill'];
                $different_user_jobseeker_certification = $user['jobseeker_certification'];
                $different_user_porfolio = "data:image/jpeg;base64," . base64_encode($user['porfolio']);
                $different_user_job_type = $user['job_type'];
                $different_user_desired_industry = $user['desired_industry'];
                $different_user_expected_salary_range = $user['expected_salary_range'];
                $different_user_willingness_to_relocate = $user['willingness_to_relocate'];
                $different_user_work_schedule_preference = $user['work_schedule_preference'];
            }
            else if($different_user_role == 'employer'){
                $different_user_company_address_city = $user['company_address_city'];
                $different_user_company_address_province = $user['company_address_province'];
                $different_user_company_address_country = $user['company_address_country'];
                $different_user_company_address_URL = $user['company_address_URL'];
                $different_user_company_industry_type = $user['company_industry_type'];
                $different_user_company_size = $user['company_size'];
                $different_user_position_in_company = $user['position_in_company'];
                $different_user_years_with_company = $user['years_with_company'];
                $different_user_preferred_hiring_location = $user['preferred_hiring_location'];
                $different_user_salary_range = $user['salary_range'];
            }
        }
    } else {
        header('Location: opportUnity_login.php');
        exit();
    }
}
else{
    // ito ay para ipakita yung edit button kapag nagview ng profile yung sarili nya
    $edit = true;

    // Retrieve session variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;

    // Database connection
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_username='$userNAME' AND user_password='$userPASSWORD'";
    $result = mysqli_query($conn, $sql);

    $pic_identifier = '';

    if ($result && ($userNAME != null && $userPASSWORD != null)) {
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            $first = $user['user_firstname'];
            $last = $user['user_lastname'];
            $name = $first . " " . $last;
            $username = $user['user_username'];
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $role = $user['user_type'];
            //para lang makita if may laman bang picture yung blob or wala
            $pic_identifier = base64_encode($user['profile_photo']);
            $user_notification = $user['user_notification'];

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
    } else {
        header('Location: opportUnity_login.php');
        exit();
    }
}
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM `admin` WHERE `user_ID` = '$userID'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $AccountState = $user['AccountState'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="view_profile.css">
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

    <div id="container">
        <div class="profile-header">
            <span class="account-state"><?=$AccountState?></span>
            <?php if ($edit) { ?>
                <form action="opportUnity_edit_profile.php" method="POST">
                    <input type="hidden" name="role" value="<?=$role?>">
                    <input type="hidden" name="first_name" value="<?=$first?>">
                    <input type="hidden" name="last_name" value="<?=$last?>">
                    <input type="hidden" name="email" value="<?=$username?>">
                    <input type="hidden" name="password" value="<?=$userPASSWORD?>">
                    <input type="hidden" name="user_ID" value="<?=$user_ID?>">
                    <button type="submit" class="edit-profile-btn">Edit Profile</button>
                </form>
            <?php } ?>
        </div>
        
        <!-- job seeker side -->
        <?php if (!(isset($different_user_role))) { 
            if ($role == 'employee') { ?>
            <!-- Personal Details -->
            <h1 class="title">Personal Details</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Name</h4>
                    <p><?=$name?></p>
                </div>
                <div class="card">
                    <h4>Age</h4>
                    <p><?=$age?></p>
                </div>
                <div class="card">
                    <h4>Date of Birth</h4>
                    <p><?=$date_of_birth?></p>
                </div>
                <div class="card">
                    <h4>Contact Number</h4>
                    <p><?=$use_phone_number?></p>
                </div>
                <div class="card">
                    <h4>Profile Picture</h4>
                    <a href="<?=$pic?>">
                        <img class="profimg" src="<?=$pic?>" alt="" width="200px" height="200px">
                    </a>
                </div>
                <div class="card">
                    <h4>Personal Email Address</h4>
                    <p><?=$username?></p>
                </div>
                <div class="card">
                    <h4>Address (city/province/country)</h4>
                    <p><?=$city?> <?=$province?> <?=$country?></p>
                </div>
            </div> 

            <!-- Professional Details -->
            <h1 class="title">Professional Details</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Educational Background (school, degree, year graduated)</h4>
                    <p><?=$school?> <br> <?=$degree?> <br> <?=$year_graduated?></p>
                </div>
                <div class="card">
                    <h4>LinkedIn Profile URL</h4>
                    <p><?=$user_URL?></p>
                </div>
                <div class="card">
                    <h4>Portfolio/Resume Upload</h4>
                    <img src="<=$porfolio?>" alt="">
                </div>
                <div class="card">
                    <h4>Work Experience (job title, company, years of service, job description)</h4>
                    <p><?=$job_title_experience?> <br> <?=$year_of_service_experience?> <br> <?=$job_description_jobseeker?> </p>
                </div>
            </div> 

            <!-- Preferences -->
            <h1 class="title">Preferences</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Job Preferences</h4>
                    <p><?=$work_schedule_preference?></p>
                </div>
                <div class="card">
                    <h4>Desired Industry</h4>
                    <p><?=$desired_industry ?></p>
                </div>
                <div class="card">
                    <h4>Expected Salary Range</h4>
                    <p><?=$expected_salary_range ?></p>
                </div>
                <div class="card">
                    <h4>Willingness to Relocate</h4>
                    <p><?=$willingness_to_relocate?></p>
                </div>
            </div> 


        <!-- employer side -->
        <?php } elseif ($role == 'employer') { ?>
            <!-- Personal Details -->
            <h1 class="title">Personal Details</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Name</h4>
                    <p><?=$name?></p>
                </div>
                <div class="card">
                    <h4>Age</h4>
                    <p><?=$age?></p>
                </div>
                <div class="card">
                    <h4>Date of Birth</h4>
                    <p><?=$date_of_birth?></p>
                </div>
                <div class="card">
                    <h4>Contact Number</h4>
                    <p><?=$use_phone_number?></p>
                </div>
                <div class="card">
                    <h4>Profile Picture</h4>
                    <img class="profimg" src="<?=$pic?>" alt="" width="100px" height="100px">
                </div>
                <div class="card">
                    <h4>Personal Email Address</h4>
                    <p><?=$username?></p>
                </div>
                <div class="card">
                    <h4>Address (city/province/country)</h4>
                    <p><?=$city?> <?=$province?> <?=$country?></p>
                </div>
            </div>

            <!-- Company Details -->
            <h1 class="title">Company Details</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Company Address (city/province/country)</h4>
                    <p><?=$company_address_city?> <?=$company_address_province?> <?=$company_address_country?></p>
                </div>
                <div class="card">
                    <h4>Company Website</h4>
                    <p class="companyurl"><?=$company_address_URL?></p>
                </div>
                <div class="card">
                    <h4>Industry Type</h4>
                    <p><?=$company_industry_type?></p>
                </div>
                <div class="card">
                    <h4>Company Size</h4>
                    <p><?=$company_size?></p>
                </div>
            </div> 

            <!-- Professional Details -->
            <h1 class="title">Professional Details</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Position in the Company</h4>
                    <p><?=$position_in_company?></p>
                </div>
                <div class="card">
                    <h4>Years in Company</h4>
                    <p><?=$years_with_company ?></p>
                </div>
                <div class="card">
                    <h4>LinkedIn Profile URL</h4>
                    <p><?=$user_URL?></p>
                </div>
            </div>

            <!-- Preferences for Job Postings -->
            <h1 class="title">Preferences for Job Postings</h1>
            <div class="profile-container">
                <div class="card">
                    <h4>Types of Roles Frequently Posted</h4>
                    <p><?=$company_industry_type?></p>
                </div>
                <div class="card">
                    <h4>Preferred Hiring Location</h4>
                    <p><?=$preferred_hiring_location ?></p>
                </div>
                <div class="card">
                    <h4>Typical Hiring Budget/Salary Range</h4>
                    <p><?=$salary_range ?></p>
                </div>
            </div>

            <!-- dito nagstart control z -->

            <?php 
                }

                } else {
            
            ?>
                <!-- gagana lang tong else statement kapag viview ng user and profile ng ibang user -->

                <?php 
                    if($different_user_role == 'employee') 
                    {
                ?>

                        <!-- Personal Details -->
                        <h1 class="title">Personal Details</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Name</h4>
                                <p><?=$different_user_name?></p>
                            </div>
                            <div class="card">
                                <h4>Age</h4>
                                <p><?=$different_user_age?></p>
                            </div>
                            <div class="card">
                                <h4>Date of Birth</h4>
                                <p><?=$different_user_date_of_birth?></p>
                            </div>
                            <div class="card">
                                <h4>Contact Number</h4>
                                <p><?=$different_user_use_phone_number?></p>
                            </div>
                            <div class="card">
                                <h4>Profile Picture</h4>
                                <img class="profimg" src="<?=$different_user_pic?>" alt="" width="100px" height="100px">
                            </div>
                            <div class="card">
                                <h4>Personal Email Address</h4>
                                <p><?=$different_user_email?></p>
                            </div>
                            <div class="card">
                                <h4>Address (city/province/country)</h4>
                                <p><?=$different_user_city?> <?=$different_user_province?> <?=$different_user_country?></p>
                            </div>
                        </div>

                        <!-- Professional Details -->
                        <h1 class="title">Professional Details</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Educational Background (school, degree, year graduated)</h4>
                                <p><?=$different_user_school?> <br> <?=$different_user_degree?> <br> <?=$different_user_year_graduated?></p>
                            </div>
                            <div class="card">
                                <h4>LinkedIn Profile URL</h4>
                                <p><?=$different_user_user_URL?></p>
                            </div>
                            <div class="card">
                                <h4>Portfolio/Resume Upload</h4>
                                <img src="<=$different_user_porfolio?>" alt="">
                            </div>
                            <div class="card">
                                <h4>Work Experience (job title, company, years of service, job description)</h4>
                                <p><?=$different_user_job_title_experience?> <br> <?=$different_user_year_of_service_experience?> <br> <?=$different_user_job_description_jobseeker?> </p>
                            </div>
                        </div> 

                        <!-- Preferences -->
                        <h1 class="title">Preferences</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Job Preferences</h4>
                                <p><?=$different_user_work_schedule_preference?></p>
                            </div>
                            <div class="card">
                                <h4>Desired Industry</h4>
                                <p><?=$different_user_desired_industry ?></p>
                            </div>
                            <div class="card">
                                <h4>Expected Salary Range</h4>
                                <p><?=$different_user_expected_salary_range ?></p>
                            </div>
                            <div class="card">
                                <h4>Willingness to Relocate</h4>
                                <p><?=$different_user_willingness_to_relocate?></p>
                            </div>
                        </div>

                <?php 

                    } 
                    else if ($different_user_role == 'employer')
                    {

                ?>

                        <!-- Personal Details -->
                        <h1 class="title">Personal Details</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Name</h4>
                                <p><?=$different_user_name?></p>
                            </div>
                            <div class="card">
                                <h4>Age</h4>
                                <p><?=$different_user_age?></p>
                            </div>
                            <div class="card">
                                <h4>Date of Birth</h4>
                                <p><?=$different_user_date_of_birth?></p>
                            </div>
                            <div class="card">
                                <h4>Contact Number</h4>
                                <p><?=$different_user_use_phone_number?></p>
                            </div>
                            <div class="card">
                                <h4>Profile Picture</h4>
                                <img class="profimg" src="<?=$different_user_pic?>" alt="" width="100px" height="100px">
                            </div>
                            <div class="card">
                                <h4>Personal Email Address</h4>
                                <p><?=$different_user_email?></p>
                            </div>
                            <div class="card">
                                <h4>Address (city/province/country)</h4>
                                <p><?=$different_user_city?> <?=$different_user_province?> <?=$different_user_country?></p>
                            </div>
                        </div>

                        <!-- Company Details -->
                        <h1 class="title">Company Details</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Company Address (city/province/country)</h4>
                                <p><?=$different_user_company_address_city?> <?=$different_user_company_address_province?> <?=$different_user_company_address_country?></p>
                            </div>
                            <div class="card">
                                <h4>Company Website</h4>
                                <p class="companyurl"><?=$different_user_company_address_URL?></p>
                            </div>
                            <div class="card">
                                <h4>Industry Type</h4>
                                <p><?=$different_user_company_industry_type?></p>
                            </div>
                            <div class="card">
                                <h4>Company Size</h4>
                                <p><?=$different_user_company_size?></p>
                            </div>
                        </div> 

                        <!-- Professional Details -->
                        <h1 class="title">Professional Details</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Position in the Company</h4>
                                <p><?=$different_user_position_in_company?></p>
                            </div>
                            <div class="card">
                                <h4>Years in Company</h4>
                                <p><?=$different_user_years_with_company ?></p>
                            </div>
                            <div class="card">
                                <h4>LinkedIn Profile URL</h4>
                                <p><?=$different_user_user_URL?></p>
                            </div>
                        </div>

                        <!-- Preferences for Job Postings -->
                        <h1 class="title">Preferences for Job Postings</h1>
                        <div class="profile-container">
                            <div class="card">
                                <h4>Types of Roles Frequently Posted</h4>
                                <p><?=$different_user_company_industry_type?></p>
                            </div>
                            <div class="card">
                                <h4>Preferred Hiring Location</h4>
                                <p><?=$different_user_preferred_hiring_location ?></p>
                            </div>
                            <div class="card">
                                <h4>Typical Hiring Budget/Salary Range</h4>
                                <p><?=$different_user_salary_range ?></p>
                            </div>
                        </div>

                <?php 

                    }
            }
                ?>

    </div>    

    
    <script>

        var usertype = '<?=$role?>';

        console.log(usertype)
    
        var user_notification = "<?=$user_notification?>";
        console.log(user_notification);
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