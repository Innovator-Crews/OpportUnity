<?php
// Start the session
session_start();
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
//jobseeker userid
$userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;
require 'connection.php';

// manggagaling to sa opportunity joblistjobseeker
if(isset($_POST['id'])) $_SESSION['jobId'] = $_POST['id']; 
if(isset($_POST['jobname'])) $_SESSION['jobName'] = $_POST['jobname'];
if(isset($_POST['salary'])) $_SESSION['jobSalary'] = $_POST['salary']; 
if(isset($_POST['compname'])) $_SESSION['companyName'] = $_POST['compname'];
if(isset($_POST['jobdesc'])) $_SESSION['jobDescription'] = $_POST['jobdesc']; 
if(isset($_POST['req'])) $_SESSION['jobRequirements'] = $_POST['req'];
if(isset($_POST['qual'])) $_SESSION['jobQuality'] = $_POST['qual']; 
if(isset($_POST['expect'])) $_SESSION['jobExpectation'] = $_POST['expect'];
if(isset($_POST['different_user_userid'])) $_SESSION['different_user_userid'] = $_POST['different_user_userid'];

$conn = new mysqli("localhost", "root", "", "opportunity");
// $sql = "SELECT u.* FROM user u INNER JOIN application_logs al ON u.user_ID = al.jobseeker_userid WHERE al.jobid = '".$request."' AND al.user_status = 'Short Listed' ORDER BY al.datetime_apply DESC;";
$sql = "SELECT employer_message FROM transaction_logs WHERE jobseeker_userid = '".$userID."' AND jobid= '".$_SESSION['jobId']."' ORDER BY datetime_apply DESC LIMIT 1;";
$result = mysqli_query($conn, $sql);
$reason = mysqli_fetch_assoc($result);
$string_reason = $reason['employer_message'] ?? null;


// $_SESSION['jobId']; 
// $_SESSION['jobName'];
// $_SESSION['jobSalary']; 
// $_SESSION['companyName'];
// $_SESSION['jobDescription']; 
// $_SESSION['jobRequirements'];
// $_SESSION['jobQuality']; 
// $_SESSION['jobExpectation'];

if (isset($_SESSION['message'])) 
{ 
    echo "<script>alert('" . $_SESSION['message'] . "');</script>"; 
    // Unset the session variable to prevent the alert from showing again 
    unset($_SESSION['message']); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employment Roadmap</title>
    <link rel="stylesheet" href="view_detail.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="opportUnity_joblist_jobseeker.php"><button class="backbtn">Back to Job List</button></a>
            <div class="hdr"><h1><?php echo $_SESSION['companyName']; ?></h1></div>

            <div class="control_buttons">
                <span id="status" class="btn"></span>
                <form action="cancel.php">
                    <button id="cancelbtn" onclick="idToString(this)" class="btn">Cancel</button>
                </form>
                <form action="view_profile.php" method="POST">
                    <input type="hidden" name="different_user_userid" value="<?=$_SESSION['different_user_userid']?>">
                    <input type="hidden" name="different_user_type" value="employer">
                        <?php if ($role === 'employer'): ?>
                            <button class="btn" name="different_user_view_profile" type="submit">View Job Seeker Details</button>
                        <?php elseif ($role === 'employee'): ?>
                            <button class="btn" name="different_user_view_profile" type="submit">View Employer Details</button>
                        <?php else: ?>
                            <!-- Redirect to login page or show a generic navbar if role is not set -->
                            <li><a href="opportUnity_login.php">Login</a></li>
                        <?php endif; ?>
                    </form>
            </div>
        </div>
        <div class="list_applicants">
            <div id="list_area">
                <div class="details"><h2 class="title_det">Position:</h2><h3 class="info_dets"> <?php echo $_SESSION['jobName']; ?></h3></div>
                <div class="details"><h2 class="title_det">Salary:</h2><h3 class="info_dets"> $<?php echo $_SESSION['jobSalary']; ?></h3></div>
                <div class="details"><h2 class="title_det">Description:</h2><h5 class="info_det"> <?php echo $_SESSION['jobDescription']; ?></h5></div>
                <div class="details"><h2 class="title_det">Requirements:</h2><h5 class="info_det"> <?php echo $_SESSION['jobRequirements']; ?></h5></div>
                <div class="details"><h2 class="title_det">Quality:</h2><h5 class="info_det"> <?php echo $_SESSION['jobQuality']; ?></h5></div>
                <div class="details"><h2 class="title_det">Expectation:</h2><h5 class="info_det"> <?php echo $_SESSION['jobExpectation']; ?></h5></div>
            </div>
            <br><br><br>
        <h3 style="color:white;">
            <?php if(isset($string_reason)){?>
            Reason:
            <?=$string_reason?>
            <?php }?>
        </h3>
        </div>
    </div>

    <script>
        var jid = <?php echo $_SESSION['jobId']; ?>;
        console.log("<?=$_POST['different_user_userid']?>");
        // var ds = <?php echo $_SESSION['id']; ?>;
        // var dss = "<?php echo $_SESSION['usertype']; ?>";
        // console.log(ds);
        // console.log(dss);


        // var list = ["waiting_list", "short_listed", "rejected", "accepted"]; 
        // var intervalId = setInterval(function() { getListOfJob(sd); }, 1000);

        // console.log(sd);
        // function idToString(s) {
        //     clearInterval(intervalId);
        //     getListOfJob(s.id);
        //     intervalId = setInterval(function() { getListOfJob(s.id); }, 1000);
        // }

        setInterval(getStatus, 1000);
        function getStatus() {
            var xml = new XMLHttpRequest();
            var method = "GET";
            var url = "getDetail_part2.php?jobid=" + jid;
            var asynchronous = true;

            xml.open(method, url, asynchronous);
            xml.send();
            xml.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = this.responseText;
                    if(data == "Rejected") 
                    {
                        document.getElementById('status').style.color ="Red";
                        document.getElementById('cancelbtn').style.display ="none";
                    }
                    
                    else if(data == "Short Listed") 
                    {
                        document.getElementById('status').style.color ="Orange";
                        document.getElementById('cancelbtn').style.display ="block";
                    }
                    else if(data == "Accepted") 
                    {
                        document.getElementById('status').style.color ="Lime";
                        document.getElementById('cancelbtn').style.display ="block";
                    }
                    else 
                    {
                        document.getElementById('status').style.color ="White";
                        document.getElementById('cancelbtn').style.display ="block";
                    }
                    var htmldata = document.getElementById('status');
                    htmldata.innerHTML = data;
                }
            }

        }
    </script>
</body>
</html>
