<?php
// Start the session
session_start();

// Global variables
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$usertype = $_SESSION['usertype'] ?? null;


$conn = new mysqli("localhost", "root", "", "opportunity");

$sql = "UPDATE user SET user_notification = 0 WHERE user_ID = ?"; 
$stmt = $conn->prepare($sql); 
$stmt->bind_param("i", $userID); 
$stmt->execute();

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
if ($result && ($userNAME != null && $userPASSWORD != null)) {
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
        //para lang makita if may laman bang picture yung blob or wala
        $pic_identifier = base64_encode($user['profile_photo']);
        $user_notification = $user['user_notification'];

        // doble ganto ko kasi kapag walang nafetch or walang notif yung isang user walang mafefetch na name
    $first = $user['user_firstname'];
    $last = $user['user_lastname'];
    $name = $first . " " . $last;
    }

} else {
    session_abort();
    include 'opportUnity_login.php';
    exit();
}

$conn = new mysqli("localhost", "root", "", "opportunity");
if($usertype == "employer")
{
    $sql = "SELECT * FROM transaction_logs WHERE employer_userid = '".$userID."' ORDER BY datetime_apply DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $htmlContent = '';

    if ($result->num_rows > 0) {
        $htmlContent .= "<h2 class='header'>Job Applications Submitted to Your Job Posts</h2>"; // Header for employer logs
        while ($user = $result->fetch_assoc()) {
            $jobid = $user['jobid'];
            $job_position = $user['job_position'];
            $company_name = $user['company_name'];
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
            $jobsdec = $user['jobsdec'];
            $jobseeker_userid = $user['jobseeker_userid'];
            $datetime_apply = $user['datetime_apply'];
            $user_status = $user['user_status'];

            // Append structured content
            $htmlContent .= "<div class='user-info'>";
            $htmlContent .= "<h3>Application for $job_position</h3>"; // Job Position Header
            $htmlContent .= "<p><strong>Applicant Name:</strong> $first_name $last_name</p>";
            $htmlContent .= "<p><strong>Company:</strong> $company_name</p>";
            $htmlContent .= "<p><strong>Job Description:</strong> $jobsdec</p>";
            $htmlContent .= "<p><strong>Date Applied:</strong> $datetime_apply</p>";
            $htmlContent .= "<p><strong>Status:</strong> $user_status</p>";
            $htmlContent .= "</div>";
        }
    } else {
        $htmlContent = "<h2>No Job Applications Found</h2>"; // Message if no data
    }


}

else if($usertype == "employee")
{
    $sql = "SELECT * FROM transaction_logs WHERE jobseeker_userid = '".$userID."' ORDER BY datetime_apply DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $htmlContent = '';

    if ($result->num_rows > 0) {
        $htmlContent .= "<h2 class='header'>Your Job Applications</h2>"; // Header for employee logs
        while ($user = $result->fetch_assoc()) {
            $jobid = $user['jobid'];
            $job_position = $user['job_position'];
            $company_name = $user['company_name'];
            $jobsdec = $user['jobsdec'];
            $datetime_apply = $user['datetime_apply'];
            $user_status = $user['user_status'];
            $employer_message = $user['employer_message'];

            // Check status and style rejected applications
            if ($user_status != "Rejected") {
                $htmlContent .= "<div class='user-info'>";
                $htmlContent .= "<h3>Application for $job_position</h3>"; // Job Position Header
                $htmlContent .= "<p><strong>Company:</strong> $company_name</p>";
                $htmlContent .= "<p><strong>Job Description:</strong> $jobsdec</p>";
                $htmlContent .= "<p><strong>Date Applied:</strong> $datetime_apply</p>";
                $htmlContent .= "<p><strong>Status:</strong> $user_status</p>";
                $htmlContent .= "</div>";
            } else {
                $htmlContent .= "<div class='user-info' style='background-color:#ff474a;'>";
                $htmlContent .= "<h3>Application for $job_position (Rejected)</h3>";
                $htmlContent .= "<p><strong>Company:</strong> $company_name</p>";
                $htmlContent .= "<p><strong>Reason:</strong> $employer_message</p>";
                $htmlContent .= "<p><strong>Date Applied:</strong> $datetime_apply</p>";
                $htmlContent .= "<p><strong>Status:</strong> $user_status</p>";
                $htmlContent .= "</div>";
            }
        }
    } else {
        $htmlContent = "<h2>No Job Applications Found</h2>"; // Message if no data
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="opportUnity_notification_list.css">
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
            <!-- Dynamic Nav Links based on user type -->
            <ul class="nav-links">
                <?php if ($usertype == 'employee'): ?>
                    <li><a href="opportUnity_dashboard_jobseeker.php">Homepage</a></li>
                    <li><a href="search_page.php">Search Job</a></li>
                    <li><a href="opportUnity_all_job_posted.php">Vacant Jobs</a></li>
                    <li><a href="opportUnity_joblist_jobseeker.php">Applied List</a></li>
                <?php elseif ($usertype == 'employer'): ?>
                    <li><a href="opportUnity_dashboard_employer.php">Homepage</a></li>
                    <li><a href="opportUnity_postjob.php">Post a Job</a></li>
                    <li><a href="opportUnity_joblist_employer.php">Your Jobs</a></li>
                    <li><a href="opportUnity_applicantslist_employer.php">Applicants List</a></li>
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
        <?php echo $htmlContent; ?>
    </div>

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
