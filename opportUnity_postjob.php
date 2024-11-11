<?php
    $userNAME = $_POST['email'] ?? null;
    $userPASSWORD = $_POST['password'] ?? null;
    $userID = $_POST['userID'] ?? null;
    $name = '';
    $first = '';
    $last = '';
    $pic = '';
    $submitted = 0;

    $conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "SELECT * FROM user WHERE user_username=? AND user_password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userNAME, $userPASSWORD);
$stmt->execute();
$result = $stmt->get_result();

// If the main page cannot be accessed without logging in
// This is responsible for redirecting the user into the login page
if ($result && ($userNAME != null && $userPASSWORD != null)) {
    $num_rows = mysqli_num_rows($result);
    if ($num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        $first = $user['user_firstname'];
        $last = $user['user_lastname'];
        $name = $first . " " . $last;
        $pic = "data:image/jpeg;base64," . base64_encode($user['profile_photo']);
    }
} else {
    include 'opportUnity_login.php';
    exit();
}
    
if (isset($_POST['makejobjob_title'])) {
    $compname = $_POST['company_name'];
    $jobpos = $_POST['job_title'];
    $jobdes = $_POST['job_description'];

    $jobID = 0; // Initialize jobID

    $conn = new mysqli("localhost", "root", "", "opportunity");

    if ($conn) {
        do {
            $jobID = rand(100000000, 999999999);
            $sql = "SELECT * FROM job WHERE jobid=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $jobID);
            $stmt->execute();
            $result = $stmt->get_result();
        } while ($result->num_rows > 0);

        $sql = "INSERT INTO job (jobid, companyname, jobname, jobdesc, userid) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssi", $jobID, $compname, $jobpos, $jobdes, $userID);

        if ($stmt->execute()) {
            echo $submitted = 1;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Connection failed: " . mysqli_connect_error();
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
            <a href="opportUnity.html"><img src="logo.png" alt="OpportUnity Logo" class="navbar-logo"></a>
            <a href="opportUnity.html"><div class="logo">OpportUnity</div></a>
            <ul class="nav-links">
                <li><a href="opportUnity.html">Landing Page</a></li>
                <li><a href="#">Terms & Condition</a></li>
            </ul>
        </div>
        <div class="navbar-right">
            <div id="userMenu" class="user-menu">
                <span id="userName">Welcome, <?=$name?>!</span>
                <div id="dropdown" class="dropdown-content">
                    <a href="profile.html">View Profile</a>
                    <a href="#" onclick="logout()">Logout</a>
                </div>
            </div>
        </div>
    </nav>


    <div id="postjob-container">
        <h2>Post a Job</h2>
        <form id="postjob-form"  method="POST">
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

            
    <input type="hidden" id="userID" name="userID" value="<?= $userID ?>">
    <input type="hidden" id="uname" name="email" value="<?= $userNAME ?>">
    <input type="hidden" id="pword" name="password" value="<?= $userPASSWORD ?>">

            <button name="makejobjob_title" class="submit-btn">Post Job</button>
        </form>

        <form id="submitcompleted" action="opportUnity_dashboard_employer.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="userID" name="userID" value="<?= $userID ?>">
    <input type="hidden" id="email" name="email" value="<?= $userNAME ?>">
    <input type="hidden" id="password" name="password" value="<?= $userPASSWORD ?>">
</form>
    </div>

<script>
    var submitted = <?php echo $submitted; ?>;
    while(submitted == 1)
    {
        document.getElementById('submitcompleted').submit();
        break;
    }

</script>
</body>
</html>


