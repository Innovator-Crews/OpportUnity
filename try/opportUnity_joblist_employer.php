<?php
//global variable
session_start();
$_SESSION['user_statusDistributerzz'] = "waiting_list";
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "SELECT * FROM user WHERE user_username=? AND user_password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userNAME, $userPASSWORD);
$stmt->execute();
$result = $stmt->get_result();
$pic = '';

// If the main page cannot be accessed without logging in
// This is responsible for redirecting the user into the login page
if ($result && ($userNAME != null && $userPASSWORD != null)) {
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
        //para lang makita if may laman bang picture yung blob or wala
        $pic_identifier = base64_encode($user['profile_photo']);
        $user_notification = $user['user_notification'];
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
    <title>Job Post List</title>
    <link rel="stylesheet" href="opportUnity_joblist_employer.css">
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

    <h1 class="header">All Jobpost List</h1>
    <div class="container">
        <div id="job-list-container"></div>
    </div>

<script>
    var user_notification = "<?=$user_notification?>";
    console.log("<?=$pic?>");
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


    var uid = <?php echo $userID; ?>;
    var jobid;
    var companyname;
    var jobname;
    var jobdesc;
    var userid;
    
    var currentUrl = window.location.href;

    

    function getListOfJob() {
        // AJAX
        var xml = new XMLHttpRequest();
        var method = "GET";
        var url = "cntx_opportUnity_dashboard_employer.php?user_id="+uid;
        var asynchronous = true;

        xml.open(method, url, asynchronous);
        xml.send();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var htmldata = document.getElementById('job-list-container');
                var html = '<div class="contnr">';
                for (var i = 0; i < data.length; i++) {
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

                //html += "<img src='" + joblogo + "' width='100px' height='100px'/>";
                html += "<div class='cntx'><div class='cnpc'>";
                html += "</div>";
                html += "<h1> Job position: " + jobname + "</h1>";
                html += "<h2> Salary $" + salary + "</h2>";
                html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                // html += "<h5> Job Description: " + jobdesc + "</h5>";
                // html += "<h5> Job Requirements: " + requirements + "</h5>";
                // html += "<h5> Job Qualities: " + qualities + "</h5>";
                // html += "<h5> Job Expectations: " + expectation + "</h5>";
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

    getListOfJob();
    setInterval(getListOfJob, 100000);

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