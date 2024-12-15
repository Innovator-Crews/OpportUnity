<?php
//global variable
session_start();
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
    <title>Applicants List</title>
    <link rel="stylesheet" href="opportUnity_applicantslist_employer.css">
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

    <h1 class="header">All Applicants in overall Job you posted</h1>
    <div class="container">
        <div id="applicantslist"></div>
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


        var currentUrl = window.location.href;


        function getApplicants() {
            var uid = <?php echo json_encode($userID); ?>; 
            var xml = new XMLHttpRequest(); 
            var method = "GET"; 
            var url = "applicantslist_unlimited_opportUnity_dashboard_employer.php?user_id=" + uid; 
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
                        html += '<button class="btn" name="different_user_view_profile" type="submit">View Details</button></form>';
                        console.log(jobseeker_userid);
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

        getApplicants();
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
</body>
</html>