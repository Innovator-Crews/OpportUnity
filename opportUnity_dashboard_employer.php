<?php
$userNAME = $_POST['email'] ?? null;
$userPASSWORD = $_POST['password'] ?? null;
$userID = $_POST['userID'] ?? null;
$name = '';
$first = '';
$last = '';
$pic = '';

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

$conn = new mysqli("localhost", "root", "", "opportunity");
$sql = "SELECT * FROM job WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$job = [[], [], [], []];

while($row = mysqli_fetch_assoc($result))
{
    $job[0][] = $row['jobid'];
    $job[1][] = $row['companyname'];
    $job[2][] = $row['jobname'];
    $job[3][] = $row['jobdesc'];
}

$u_s_e_r_json = json_encode($job);
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

    <div>
        <h1>OpportUnity</h1>
        <div id="container">
            <h2>Dashboard</h2>
            <a onclick="submitForm()" class="post-job-btn">
                <button>Post a Job</button>
            </a>

            <h2>Your Job Posts</h2>
            <div id="job"></div>
        </div>
    </div>
</body>

<form id="form" action="opportUnity_postjob.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="userID" name="userID" value="<?= $userID ?>">
    <input type="hidden" id="email" name="email" value="<?= $userNAME ?>">
    <input type="hidden" id="password" name="password" value="<?= $userPASSWORD ?>">
</form>
<a href="opportUnity.html"><button>Back to Landing Page</button></a>
<div id="dataSec"></div>


<script>

function submitForm() {
    document.getElementById('form').submit();
}
    setInterval(xmlhr, 1000);

    //dito sa part na to error

function xmlhr() {
    //ajax
    var uid = <?php echo $userID; ?>;
    var xml = new XMLHttpRequest();
    var method = "GET";
    var url = "cntx_opportUnity_dashboard_employer.php?user_id="+uid;
    var asynchronous = true;

    xml.open(method, url, asynchronous);
    xml.send();
    xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            var htmldata = document.getElementById('job');
            var html = '<div class="contnr">';
            for (var i = 0; i < data.length; i++) {
                jobid = data[i].jobid;
                companyname = data[i].companyname;
                jobname = data[i].jobname;
                jobdesc = data[i].jobdesc;
                //var joblogo = data[i].joblogo;

                html += "<div class='cntx'><div class='cnpc'>";
                //html += "<img src='" + joblogo + "' width='100px' height='100px'/>";
                html += "</div>";
                html += "<h1 class='cname'> Company: " + companyname + "</h1>";
                html += "<h3> Job position: " + jobname + "</h3>";
                html += "<h5> Job Description: " + jobdesc + "</h5>";
                html += "<h5> Job ID: " + jobid + "</h5>";
                html += "</div>";
            }
            html += "</div>";
            htmldata.innerHTML = html;
        }
    }
}

</script>