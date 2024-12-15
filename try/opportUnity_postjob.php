<?php
    //global variable
    session_start();
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;
    $first = $_SESSION['firstname'];
    $last = $_SESSION['lastname'];
    $name = $first . " " . $last;
    $pic = '';
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_postjob.css">
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

    <div id="postjob-container">
        <a href="opportUnity_dashboard_employer.php"><button class="headerbtn">Back to Homepage</button></a>
        <h2>Post a Job</h2>
        <form action="postjob.php" id="postjob-form"  method="POST">
            <div class="form-group">
                <label for="job-title">Job Title</label>
                <input type="text" id="job-title" name="job_title" required>
            </div>

            <div class="form-group">
                <label for="company-name">Company Name</label>
                <input type="text" id="company-name" name="company_name" required>
            </div>

            <div class="form-group">
                <label for="job-location">Location</label>
                <input type="text" id="job-location" name="job_location" required>
            </div>

            <div class="form-group">
                <label for="job-description">Job Description</label>
                <textarea id="job-description" name="job_description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" id="salary" name="salary" required>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="qualities">Qualities</label>
                <textarea id="qualities" name="qualities" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="expectations">Expectations</label>
                <textarea id="expectations" name="expectations" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="expectations">Limit</label>
                <input type="number" id="limit" name="limit" required>
            </div>

            <!-- new added -->
            <div class="form-group">
                <label for="job-type">Job Type:</label> 
            <select id="job-type" name="job_type"> 
                <option value="full-time">Full-time</option> 
                <option value="part-time">Part-time</option> 
            </select>
            </div>

            <div class="form-group">
                <label for="work_schedule_preference">Work Schedule Preference:</label> 
            <select id="work_schedule_preference" name="work_schedule_preference"> 
                <option value="on-site">On-site</option> 
                <option value="online">Online</option> 
                <option value="blended">Blended</option> 
            </select>
            </div>

            <div class="form-group">
                <label for="company_industry_type">Company Industry Type:</label> 
            <select id="company_industry_type" name="company_industry_type"> 
                <option value="technology">Technology</option> 
                <option value="cooking">Cooking</option> 
                <option value="travel">Travel</option> 
                <option value="thrill">Thill</option> 
                <option value="sports">Sports</option> 
            </select>
            </div>
            

            

            
            <button name="makejobjob_title" class="submit-btn">Post Job</button>
        </form>
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


