<?php
    // include 'opportUnity_login.php';
    $userNAME = $_POST['email']??null;
    $userPASSWORD = $_POST['password']??null;
    $name;
    $first;
    $last;
    $pic;

        $conn = new mysqli("localhost", "root", "", "opportunity");
        $sql = "SELECT * FROM user WHERE user_username='$userNAME' AND user_password='$userPASSWORD'";
        $result = mysqli_query($conn, $sql);

        //if the main page cannot be access without logging in
        //this is responsible for redirecting the user into login page
        if($result && ($userNAME != null && $userPASSWORD != null))
        {
            $num_rows = mysqli_num_rows($result);
            if ($num_rows > 0) {
                $user = mysqli_fetch_assoc($result);
                $first = $user['user_firstname'];
                $last = $user['user_lastname'];
                $name = $first . " " . $last;
                $pic = "data:image/jpeg;base64,".base64_encode($user['profile_photo']);
            }
        }else{
            include 'opportUnity_login.php';
            exit();
        }

        //convert PHP to JSON
        $u_first = json_encode($first);
        $u_last = json_encode($last);
        $clicker = $_POST['clicker'] ?? 0;
        //this is for posting the info of the  job seeker to the databse when they click apply button
        if ($clicker == 1) {
            $companyname = $_POST['compName'];
            $firstname = $_POST['fname'];
            $lastname = $_POST['lname'];

            $conn = new mysqli("localhost", "root", "", "opportunity");
            $sql = "INSERT INTO application_logs(company_name, first_name, last_name) VALUES ('$companyname', '$firstname', '$lastname')";
            $result = mysqli_query($conn, $sql);
    
            $clicker = 0;

            $userNAME = $userNAME;
            $userPASSWORD = $userPASSWORD;
        }
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
    <!--this form is to send the name of the job seeker into a database and the company name he apply to-->
    <form id="myForm" method="POST">
        <input type="hidden" id="compName" name="compName">
        <input type="hidden" id="fname" name="fname">
        <input type="hidden" id="lname" name="lname">
        <input type="hidden" id="clicker" name="clicker">
        <input type="hidden" id="email" name="email" value="<?= $userNAME ?>">
        <input type="hidden" id="password" name="password" value="<?= $userPASSWORD ?>">
    </form>
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
                <span id="userName">Welcome, <?php echo $name; ?>!</span>
                <div id="dropdown" class="dropdown-content">
                    <a href="profile.html">View Profile</a>
                    <a id="gotoLogout">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div>
        <h1>OpportUnity</h1>
        <div id="container">
            <h2>Dashboard</h2>
            <h2>Available Jobs</h2>
            <div id="job-list-container">
            </div>
        </div>
    </div>

    <script>
    
    var jobid;
var companyname;
var jobname;
var jobdesc;

const logout = document.getElementById('gotoLogout');
logout.addEventListener('click', function() {
    // Refresh the page and prevent going back
    window.location.replace("opportUnity.html");
    //history.pushState('opportUnity.html', '', '/home');
});

setInterval(xmlhr, 1000);

function xmlhr() {
    //ajax
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
            var html = '';
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
                html += "<button onclick='applyy(this)'>APPLY</button>";
                html += "</h1></div>";
            }
            htmldata.innerHTML = html;
        }
    }
}

function applyy(button) {
    //JSON from php
    var firstname = <?php echo $u_first; ?>;
    var lastname = <?php echo $u_last; ?>;

    // Traverse the DOM to find the company name within the same div
    var compname = button.closest('.cntx').querySelector('.cname').textContent.replace(' Company: ', '');
    document.getElementById("compName").value = compname;
    document.getElementById("fname").value = firstname;
    document.getElementById("lname").value = lastname;
    //ang purpose nito is to make sure na di magrurun yung line 36 sa don sa php
    document.getElementById("clicker").value = 1;
    document.getElementById('myForm').submit();
}


</script>

</body>
</html>