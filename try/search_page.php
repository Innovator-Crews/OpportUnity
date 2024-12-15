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

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize variables
    $pic = '';
    $pic_identifier = '';
    $firstname = '';
    $lastname = '';

    // If the main page cannot be accessed without logging in
    // This is responsible for redirecting the user into the login page
    if ($userNAME && $userPASSWORD && $usertype == "employee") {
        $sql = "SELECT * FROM user WHERE user_username=? AND user_password=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userNAME, $userPASSWORD);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
            $pic_identifier = base64_encode($user['profile_photo']);
            $user_notification = $user['user_notification'];
            $firstname = $user['user_firstname'];
            $lastname = $user['user_lastname'];
        }
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
    <title>Search Page</title>
    <link rel="stylesheet" href="search_page.css">
</head>
<body>
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

    <div class="welcome-message">
        <h2>Welcome to Your Job Search</h2>
        <p>Type in a job title, company name, or keyword in the search bar below to find the best opportunities that match your skills and preferences. Let’s get started!</p>
    </div>

    <input type="search" value="" onkeyup="search(this)" placeholder="Type here Ex: Backend">
    
    <div class="content-section">
        <div id="job-list-container"></div>
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
        function search(find) {
            console.log(find.value);
            // AJAX
            var xml = new XMLHttpRequest();
            var method = "GET";
            var url = "search.php?jobSearch=" + find.value;
            var asynchronous = true;
            xml.open(method, url, asynchronous);
            xml.send();
            xml.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {
                        try {
                            var data = JSON.parse(this.responseText);
                            console.log(data);
                            console.log(data.length);
                            var htmldata = document.getElementById('job-list-container');
                            var html = '';
                            for (var i = 0; i < data.length; i++) {
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
                                var employer_userid = data[i].userid;
                                var datetime_job_created = data[i].datetime_job_created;
                                html += "<div class='cntx'><div class='cnpc'>";
                                html += "</div>";
                                html += "<h1 class='jobposition'> Job position: " + jobname + "</h1>";
                                html += "<h2> Salary $" + salary + "</h2>";
                                html += "<h3 class='cname'> Company: " + companyname + "</h3>";
                                html += "<h5> Job Description: " + jobdesc + "</h5>";
                                html += "<h5> Job Requirements: " + requirements + "</h5>";
                                html += "<h5> Job Qualities: " + qualities + "</h5>";
                                html += "<h5> Job Expectations: " + expectation + "</h5>";
                                html += "<h5 class='jid'> Job ID: " + jobid + "</h5>";
                                html += "<form action='apply.php' method='POST'>";
                                html += "<input type='hidden' name='jobpos' value='" + jobname + "'>";
                                html += "<input type='hidden' name='compName' value='" + companyname + "'>";
                                html += "<input type='hidden' name='fname' value='" + <?= json_encode($firstname) ?> + "'>";
                                html += "<input type='hidden' name='lname' value='" + <?= json_encode($lastname) ?> + "'>";
                                html += "<input type='hidden' name='job_id' value='" + jobid + "'>";
                                html += "<input type='hidden' name='user_id' value='" + employer_userid + "'>";
                                html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                                html += "<button type='sumbit'>APPLY</button></form>";
                                html += "</div>";
                            }
                            htmldata.innerHTML = html;
                        } catch (e) {
                            console.error("Error parsing JSON:", e);
                        }
                    } else {
                        console.error("AJAX request failed with status:", this.status);
                    }
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

    </script>
</body>
</html>
