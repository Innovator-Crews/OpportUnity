<?php
// Start the session
session_start();

// Retrieve session variables
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$usertype = $_SESSION['usertype'] ?? null;

// Database connection
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
if ($result && ($userNAME != null && $userPASSWORD != null) && $usertype == "employee") {
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
    if ($usertype == "employer") {
        // Redirect back to the employer dashboard after successful deletion
        header("Location: opportUnity_dashboard_employer.php");
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
    <link rel="stylesheet" href="opportUnity_dashboard_jobseeker.css">
    <link rel="icon" type="image/png" href="faviconlogo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
   
</head>
<body>
    <!-- Form to send the name of the job seeker into a database and the company name he applies to -->
    <form action="apply.php" id="myForm" method="POST">
        <input type="hidden" id="jobpos" name="jobpos">
        <input type="hidden" id="compName" name="compName">
        <input type="hidden" id="fname" name="fname">
        <input type="hidden" id="lname" name="lname">
        <input type="hidden" id="job_id" name="job_id">
        <input type="hidden" id="user_id" name="user_id">
        <input type="hidden" id="url" name="currentUrl">
    </form>

    <!-- Nav bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <img onclick="logout()" src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div onclick="logout()" class="logo">OpportUnity</div>
            <ul class="nav-links">
                <li><a href="opportUnity_dashboard_jobseeker.php">Homepage</a></li>
                <li><a href="search_page.php">Search Job</a></li>
                <li><a href="opportUnity_all_job_posted.php">Vacant Jobs</a></li>
                <li><a href="opportUnity_joblist_jobseeker.php">Applied List</a></li>
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
    
        <div class="hero">
            <a href="search_page.php" class="post-job-btn">
                <button>Search Job</button>
            </a>
        </div>

        <div class="containermid">

            <!-- Available Jobs Section -->
            <div class="link-container">
                <h2>Available Jobs</h2>
                <a href="opportUnity_all_job_posted.php">
                    <button class="arrow">View more Available Jobs</button>
                </a>
            </div>
            <div class="content-section">
                <div id="job-list-container"></div>
            </div>

            <!-- Recent Applied Section -->
            <div class="link-container">
                <h2>Recent Applied Jobs</h2>
                <a href="opportUnity_applicantslist_employer.php">
                    <button class="arrow">View more Applicants</button>
                </a>
            </div>
            <div class="content-section">
                <div id="appliedJobs"></div>
            </div>

         </div> 
    </div>

    <script>

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


        var currentUrl = window.location.href;

        function xmlhr() {
            // AJAX
            var xml = new XMLHttpRequest();
            var method = "GET";
            var url = "cntx_opportUnity_dashboard_jobseeker.php";
            var asynchronous = true;

            xml.open(method, url, asynchronous);
            xml.send();
            xml.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var htmldata = document.getElementById('job-list-container');
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
                        //var joblogo = data[i].joblogo;

                        html += "<div class='cntx'><div class='cnpc'>";
                        //html += "<img src='" + joblogo + "' width='100px' height='100px'/>";
                        html += "</div>";
                        html += "<h1 class='jobposition'> Job position: " + jobname + "</h1>";
                        html += "<h2> Salary $" + salary + "</h2>";
                        html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                        html += "<h5> Job Description: " + jobdesc + "</h5>";
                        html += "<h5> Job Requirements: " + requirements + "</h5>";
                        html += "<h5> Job Qualities: " + qualities + "</h5>";
                        html += "<h5> Job Expectations: " + expectation + "</h5>";
                        html += "<h5 class='jid'> Job ID: " + jobid + "</h5>";
                        html += "<input type='hidden' class='employerid' name='user_id' value='" + userid + "'>";
                        html += "<button onclick='applyy(this)' class='applybtn'>APPLY</button>";
                        html += "</h1></div>";
                    }
                    htmldata.innerHTML = html;
                }
            }
        }


        function applyy(button) {
            // JSON from PHP
            var firstname = "<?php echo htmlspecialchars($first); ?>";
            var lastname = "<?php echo htmlspecialchars($last); ?>";

            // Traverse the DOM to find the company name within the same div
            var datatype_compname = button.closest('.cntx').querySelector('.cname').textContent.replace(' Company: ', '');
            var datatype_job_id = button.closest('.cntx').querySelector('.jid').textContent.replace(' Job ID: ', '');
            var datatype_jobposition = button.closest('.cntx').querySelector('.jobposition').textContent.replace(' Job position: ', '');
            var datatype_userid = button.closest('.cntx').querySelector('.employerid').value;
            document.getElementById("compName").value = datatype_compname;
            document.getElementById("fname").value = firstname;
            document.getElementById("lname").value = lastname;
            document.getElementById("job_id").value = datatype_job_id;
            document.getElementById("jobpos").value = datatype_jobposition;
            document.getElementById("user_id").value = datatype_userid;
            document.getElementById("url").value = currentUrl;
            document.getElementById('myForm').submit();
        }
        var uid = <?php echo $userID; ?>;
        function getListOfJob() {
            // AJAX
            var xml = new XMLHttpRequest();
            var method = "GET";
            var url = "joblist_opportUnity_dashboard_jobseeker.php?user_id=" + uid;
            var asynchronous = true;

            xml.open(method, url, asynchronous);
            xml.send();
            xml.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var htmldata = document.getElementById('appliedJobs');
                    var html = '<div class="contnr">';
                    for (var i = 0; i < 3; i++) { // Adjust the loop limit
                        if (data[i]) {
                            // Retrieve the data using AJAX from SQL
                            var jobid = data[i].jobid;
                            var companyname = data[i].companyname;
                            var jobname = data[i].jobname;
                            var jobdesc = data[i].jobdesc;
                            var job_location = data[i].job_location;
                            var salary = data[i].salary;
                            var requirements = data[i].requirements;
                            var qualities = data[i].qualities;
                            var expectation = data[i].expectation;
                            var userid = data[i].userid;
                            var datetime_job_created = data[i].datetime_job_created;
                            var jobstatus = data[i].job_status;
                            var employer_userid = data[i].userid;

                            html += "<div class='cntx'><div class='cnpc'>";
                            html += "</div>";
                            html += "<h1 class='jobposition'> Job position: " + jobname + "</h1>";
                            html += "<h2> Salary $" + salary + "</h2>";
                            html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                            html += "<div style='width:350px; display:flex; justify-content:space-between;'><h5> Job ID: " + jobid + "</h5> <h5 style='width:90px;'> Status: " + jobstatus + "</h5></div>";

                            // View Button Form
                            html += '<div style="display:flex; justify-content:space-around; width:100%;">';
                            html += '<form action="view_detail.php" method="POST">';
                            html += '<input type="hidden" name="id" value="' + jobid + '">';
                            html += '<input type="hidden" name="jobname" value="' + jobname + '">';
                            html += '<input type="hidden" name="salary" value="' + salary + '">';
                            html += '<input type="hidden" name="compname" value="' + companyname + '">';
                            html += '<input type="hidden" name="jobdesc" value="' + jobdesc + '">';
                            html += '<input type="hidden" name="req" value="' + requirements + '">';
                            html += '<input type="hidden" name="qual" value="' + qualities + '">';
                            html += '<input type="hidden" name="expect" value="' + expectation + '">';
                            html += '<input type="hidden" name="different_user_userid" value="' + employer_userid + '">';
                            html += '<button class="btn" type="submit">View details</button></form>';
                            

                            var msg = 'Are you sure you want to cancel this job?';
                            // Cancel Button Form
                            html += '<form action="cancel.php" method="POST" onsubmit="return confirm(\'' + msg + '\')">';
                            html += '<input type="hidden" name="id" value="' + jobid + '">';
                            html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                            html += '<button class="btn" type="submit">Cancel</button></form>';

                            html += "</div></div>";
                        }
                    }
                    htmldata.innerHTML = html;
                }
            };
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

        getListOfJob();
        xmlhr();

        setInterval(xmlhr, 100000);
        setInterval(getListOfJob, 100000);

    </script>
</body>
</html>
