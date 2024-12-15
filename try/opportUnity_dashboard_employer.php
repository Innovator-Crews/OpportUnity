<?php
// Start the session
session_start();
$_SESSION['user_statusDistributerzz'] = "waiting_list";

// Global variables
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$usertype = $_SESSION['usertype'] ?? null;

$conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "SELECT * FROM user WHERE user_username=? AND user_password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userNAME, $userPASSWORD);
$stmt->execute();
$result = $stmt->get_result();

// Initialize $pic
$pic = '';
$pic_identifier = '';

// If the main page cannot be accessed without logging in
// This is responsible for redirecting the user into the login page
if ($result && ($userNAME != null && $userPASSWORD != null) && $usertype == "employer") {
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
        //para lang makita if may laman bang picture yung blob or wala
        $pic_identifier = base64_encode($user['profile_photo']);
        $user_notification = $user['user_notification'];
    }
} 
// If $_SESSION['usertype'] is set
else if (isset($usertype)) {
    // Check if the usertype is employee
    if ($usertype == "employee") {
        // Redirect back to the employer dashboard after successful deletion
        header("Location: opportUnity_dashboard_jobseeker.php");
        exit;
    } else {
        // Redirect to login if usertype is not employee
        session_abort();
        include 'opportUnity_login.php';
        exit();
    }
} else {
    session_abort();
    include 'opportUnity_login.php';
    exit();
}

$first = $_SESSION['firstname'];
$last = $_SESSION['lastname'];
$name = $first . " " . $last;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_dashboard_employer.css">
    <link rel="icon" type="image/png" href="faviconlogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">

</head>

<body>
    <!-- Nav bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <img onclick="logout()" src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div onclick="logout()" class="logo">OpportUnity</div>
            <ul class="nav-links">
                <li><a href="opportUnity_dashboard_employer.php">Homepage</a></li>
                <li><a href="opportUnity_postjob.php">Post a Job</a></li>
                <li><a href="opportUnity_joblist_employer.php">Your Jobs</a></li>
                <li><a href="opportUnity_applicantslist_employer.php">Applicants List</a></li>
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
        <div class="containermid">
            <!-- Hero Section -->
            <div class="hero">
                <a href="opportUnity_postjob.php" class="post-job-btn">
                    <button>Post a Job</button>
                </a>
            </div>

            <!-- Job Posts Section -->
            <div class="link-container">
                <h2>Your Job Posts</h2>
                <a href="opportUnity_joblist_employer.php">
                    <button class="arrow">View more Job Post</button>
                </a>
            </div>
            <div class="content-section">
                <div id="job"></div>
            </div>

            <!-- Applicants Section -->
            <div class="link-container">
                <h2>Applicants</h2>
                <a href="opportUnity_applicantslist_employer.php">
                    <button class="arrow">View more Applicants</button>
                </a>
            </div>
            <div class="content-section">
                <div id="applicantslist"></div>
            </div>
        </div>

    </div>
</body>

<script>
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


    
    //dito sa part na to error

    
    // Get the full URL of the current page
    var currentUrl = window.location.href;
    console.log(currentUrl);


    function jobpost() {
        //ajax
        var uid = <?php echo $userID; ?>;
        var xml = new XMLHttpRequest();
        var method = "GET";
        var url = "joblist_opportUnity_dashboard_employer.php?user_id="+uid;
        var asynchronous = true;



        xml.open(method, url, asynchronous);
        xml.send();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var htmldata = document.getElementById('job');
                var html = '<div class="contnr">';
                for (var i = 0; i < data.length; i++) {
                    //retreive the data using ajax from sql
                    jobid = data[i].jobid;
                    companyname = data[i].companyname;
                    jobname = data[i].jobname;
                    jobdesc = data[i].jobdesc;
                    job_location = data[i].job_location;
                    salary = data[i].salary;
                    requirements = data[i].requirements;
                    qualities = data[i].qualities;
                    expectation = data[i].expectation;
                    userid = data[i].userid;
                    datetime_job_created = data[i].datetime_job_created;
                    expectations = data[i].expectations;
                    job_type = data[i].job_type;
                    work_schedule_preference = data[i].work_schedule_preference;
                    company_industry_type = data[i].company_industry_type;  
                    //var joblogo = data[i].joblogo;

                    html += "<div class='cntx'><div class='cnpc'>";
                    //html += "<img src='" + joblogo + "' width='100px' height='100px'/>";
                    html += "</div>";
                    html += "<h1> Job position: " + jobname + "</h1>";
                    html += "<h2> Salary $" + salary + "</h2>";
                    html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                    html += "<h5> Job Description: " + jobdesc + "</h5>";
                    html += "<h5> Job Requirements: " + requirements + "</h5>";
                    html += "<h5> Job Qualities: " + qualities + "</h5>";
                    html += "<h5> Job Expectations: " + expectation + "</h5>";
                    html += "<h5> Job ID: " + jobid + "</h5>";

                    // View details Button Form
                    html += '<div style="display:flex; justify-content:space-around; width:100%;">';
                    html += '<form action="view_job.php" method="post">'
                    html += '<input type="hidden" name="id" value="'+jobid+'">';
                    html += '<input type="hidden" name="compname" value="'+companyname+'">';
                    html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                    html += '<button class="btn" type="submit">View details</button></form>';

                    // Edit Button Form
                    html += '<form action="edit_job.php" method="POST">'
                    html += '<input type="hidden" name="id" value="'+jobid+'">';
                    html += '<input type="hidden" name="job_title" value="'+jobname+'">';
                    html += '<input type="hidden" name="company_name" value="'+companyname+'">';
                    html += '<input type="hidden" name="job_location" value="'+job_location+'">';
                    html += '<input type="hidden" name="salary" value="'+salary+'">';
                    html += '<input type="hidden" name="job_description" value="'+jobdesc+'">';
                    html += '<input type="hidden" name="requirements" value="'+requirements+'">';
                    html += '<input type="hidden" name="qualities" value="'+qualities+'">';
                    html += '<input type="hidden" name="job_type" value="'+job_type+'">';
                    html += '<input type="hidden" name="work_schedule_preference" value="'+work_schedule_preference+'">';
                    html += '<input type="hidden" name="company_industry_type" value="'+company_industry_type+'">';
                    // dito banda error
                    html += '<input type="hidden" name="expectations" value="'+expectations+'">';
                    html += '<button class="btn" type="submit">Edit</button></form>';
                    

                    var msg = 'Are you sure you want to delete this job?';
                    // Delete Button Form
                    html += '<form action="delete_job.php" method="POST" onsubmit="return confirm(\'' + msg + '\')">';
                    html += '<input type="hidden" name="id" value="'+jobid+'">';
                    html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                    html += '<button class="btn" type="submit">Delete Job</button></form>';
                    
                    html += "</div></div>";
                }
                html += "</div>";
                htmldata.innerHTML = html;
            }
        }
    }
 
    function getApplicants() {
        var uid = <?php echo json_encode($userID); ?>; 
        var xml = new XMLHttpRequest(); 
        var method = "GET"; 
        // change this into cntx
        var url = "applicantslist_opportUnity_dashboard_employer.php?user_id=" + uid; 
        var asynchronous = true;

        xml.open(method, url, asynchronous);
        xml.send();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var htmldata = document.getElementById('applicantslist');
                var html = '<div class="contnr">';
                for (var i = 0; i < data.length; i++) {
                    // Retrieve the data using AJAX from SQL
                    var jobid = data[i].jobid;
                    var companyname = data[i].company_name;
                    var jobname = data[i].job_position;
                    var first_n = data[i].first_name;
                    var last_n = data[i].last_name;
                    var fullname = first_n + " " + last_n;
                    var emp_userid = data[i].employer_userid;
                    var jobseeker_userid = data[i].jobseeker_userid;
                    var datetime_job_created = data[i].datetime_apply;

                    html += "<div class='cntx'><div class='cnpc'>";
                    html += "</div>";
                    html += "<h2> Name: " + fullname + "</h2>";
                    html += "<h3> Job position: " + jobname + "</h3>";
                    html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                    html += "<input type='hidden' class='employerid' value='" + emp_userid + "'>";
                    html += '<div style="display:flex; justify-content:space-between; width:90%;">';
                    html += "<h5> Applied time: " + datetime_job_created + "</h5>";
                    html += "<h5> Job ID: " + jobid + "</h5>";
                    html += "</div>";

                    // Details Button Form
                    html += '<div style="display:flex; justify-content:space-between; width:90%;">';
                    html += '<form action="view_profile.php" method="POST">';
                    html += '<input type="hidden" name="jobid" value="'+jobid+'">';
                    html += '<input type="hidden" name="different_user_userid" value="' + jobseeker_userid + '">'; 
                    html += '<input type="hidden" name="different_user_type" value="employee">';
                    html += '<button class="btn" type="submit">View Details</button></form>';
                    
                    var msg = 'Are you sure you want to decline this user application?';
                    // Decline Button Form
                    html += '<form action="decline.php" method="POST" onsubmit="return confirm(\'' + msg + '\')">';
                    html += '<input type="hidden" name="id" value="'+jobid+'">';
                    html += '<input type="hidden" name="jobseekid" value="'+jobseeker_userid+'">';
                    html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                    html += '<button class="btn" type="submit">Decline</button></form>';
                    
                    html += "</div></div>";
                }
                html += "</div>";
                htmldata.innerHTML = html;
            }
        }
    }

    jobpost();
    getApplicants();
    setInterval(jobpost, 10000);
    setInterval(getApplicants, 100000);

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


