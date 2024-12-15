<?php
// Start the session
session_start();
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;
require 'connection.php';


if(isset($_POST['currentUrl'])) $_SESSION['currentUrl'] = $_POST['currentUrl'];
if(isset($_POST['id'])) $_SESSION['jobId'] = $_POST['id']; 
if(isset($_POST['compname'])) $_SESSION['companyName'] = $_POST['compname'];
$currentUrl = $_SESSION['currentUrl'];


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
    <style>
        /* Color Palette */
        :root {
            --nav-bg: #000814;
            --primary-blue: #001D3D;
            --secondary-blue: #003566;
            --highlight-yellow: #FFC300;
            --accent-yellow: #FFD60A;
            --text-light: #F0F0F0;
            --text-muted: #CCCCCC;
            --btn-hover-bg: #FFFFFF;
            --btn-hover-text: #001D3D;
        }

        /* Reset and Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: var(--primary-blue);
            color: var(--text-light);
            font-size: 16px;
            line-height: 1.5;
        }

        /* Container Layout */
        .container {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        /* Header Section */
        .header {
            width: 100%;
            height: 150px;
            background-color: var(--nav-bg);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 2px solid var(--secondary-blue);
        }

        .backbtn {
            padding: 15px 20px;
            background-color: var(--primary-blue);
            color: white;
            border: 5px solid var(--primary-blue);
            border-radius: 15px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: 
                background-color 0.5s ease-in-out, 
                padding 0.5s ease-in-out, 
                color 0.5s ease-in-out, 
                font-weight 0.5s ease-in-out
            ;
        }

        .backbtn:hover {
            color: var(--primary-blue);
            font-weight: 700;
            background-color: var(--accent-yellow);
            padding: 15px 50px;
            border: 5px solid var(--primary-blue);
        }

        .hdr h1 {
            margin: 2px 20px;
            padding: 10px 20px;
            width: 100%;
            font-size: 24px;
            font-weight: bold;
            color: var(--highlight-yellow);
            flex-wrap: wrap;
        }

        /* Control Buttons */
        .control_buttons {
            border-radius: 40px;
            width: 60%;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            width: 100%;
            background-color: var(--secondary-blue);
            padding: 10px 0;
            border-bottom: 2px solid var(--nav-bg);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .control_buttons button {
            padding: 15px 20px;
            background-color: var(--primary-blue);
            color: white;
            border: 5px solid var(--primary-blue);
            border-radius: 15px;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            transition: 
                background-color 0.5s ease-in-out, 
                padding 0.5s ease-in-out, 
                color 0.5s ease-in-out, 
                font-weight 0.5s ease-in-out
            ;
        }

        .control_buttons button:hover {
            color: var(--primary-blue);
            font-weight: 700;
            background-color: var(--accent-yellow);
            padding: 15px 50px;
            border: 5px solid var(--primary-blue);
        }

        /* Applicant List */
        .list_applicants {
            flex: 1;
            background-color: var(--primary-blue);
            padding: 20px;
            overflow-y: auto;
        }

        #list_area {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Job Details */
        .cntx {
            background: linear-gradient(135deg, var(--secondary-blue), var(--primary-blue));
            color: var(--text-light);
            padding: 20px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
            display: flex;
            flex-direction: row;
            /* align-content: center; */
            align-items: center;
            justify-content: space-around;
        }

        .cntx:hover {
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.5);
            transform: translateY(-8px);
            background-color: var(--nav-bg);
        }

        .cntx h3, 
        .cntx h5 {
            margin: 8px 0;
            font-size: 16px;
            color: var(--text-muted);
        }

        .cntx h3 {
            font-weight: bold;
            color: var(--highlight-yellow);
        }

        .cntx .btn {
            gap: 10px;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: var(--highlight-yellow);
            color: var(--primary-blue);
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .cntx .btn:hover {
            background-color: var(--accent-yellow);
            color: var(--nav-bg);
        }

        /* Popup Form */
        #form_for_reason {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 800px;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 20px;
            border-radius: 8px;
            display: none;
            z-index: 1000;
        }

        #form_for_reason textarea {
            width: 100%;
            height: 150px;
            background-color: var(--secondary-blue);
            color: var(--text-light);
            padding: 10px;
            border-radius: 5px;
            border: none;
            resize: none;
            font-size: 16px;
        }

        #form_for_reason button {
            margin-top: 20px;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            color: var(--text-light);
            background-color: var(--secondary-blue);
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #form_for_reason button:hover {
            background-color: var(--highlight-yellow);
            color: var(--primary-blue);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .control_buttons {
                flex-direction: column;
            }

            .control_buttons button {
                width: 100%;
                margin: 5px 0;
            }
        }

        .btnview{
            margin-top: 10px;
            justify-content: center;
            width: 100%;
            border: 3px solid var(--nav-bg);
            border-radius: 5px;
            background-color: var(--white);
            padding: 12px 20px;
            transition: all 0.5s ease-in-out;
            color: var(--white);
        }

        .btnview:hover{
            cursor: pointer;
            color: var(--highlight-yellow);
            background-color: var(--primary-blue);
        }


        .cntx .btn{
            margin-top: 10px;
            justify-content: center;
            width: 50%;
            border: 3px solid var(--nav-bg);
            border-radius: 5px;
            background-color: var(--white);
            padding: 5px 20px;
            transition: all 0.5s ease-in-out;
            color: var(--white);
        }

        .cntx .btn:hover{
            cursor: pointer;
            color: var(--highlight-yellow);
            background-color: var(--primary-blue);
        }

        .btns {
            display: flex;
            width: auto;
            justify-content: space-around;
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="form_for_reason" style="height:100%; width:100%; background-color:black; position:absolute; display:none; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div style="height:100%; width:100%; display:flex; flex-direction:column; align-items:center;">
                <button style="height:60px; width:60px;" onclick="form_exit()">←</button>
                <div id="reasonform">

                </div>
            </div>
        </div>
        
        <div class="header">
            <a href="<?=$currentUrl?>"><button class="backbtn">Back to Job List</button></a>
            <div class="hdr"><h1><?php echo $_SESSION['companyName']; ?></h1></div>
            <div class="control_buttons">
                <button id="waiting_list" onclick="idToString(this)" class="btn">Waiting List</button>
                <button id="short_listed" onclick="idToString(this)" class="btn">Short Listed</button>
                <button id="rejected" onclick="idToString(this)" class="btn">Rejected</button>
                <button id="accepted" onclick="idToString(this)" class="btn">Accepted</button>
            </div>
        </div>
        <div class="list_applicants">
            <div id="list_area"></div>
        </div>
    </div>

    <script>
        var jid = <?php echo $_SESSION['jobId']; ?>;
        var sd = "<?php echo $_SESSION['user_statusDistributerzz']; ?>";
        // pang form to kapag saasabihin ng employer reason
        var rejectedfrm = '';
        var short_listedfrm = '';
        var acceptedfrm = '';

        var list = ["waiting_list", "short_listed", "rejected", "accepted"]; 
        var intervalId = setInterval(function() { getListOfJob(sd); }, 100000);

        console.log(sd);
        function idToString(s) {
            clearInterval(intervalId);
            getListOfJob(s.id);
            intervalId = setInterval(function() { getListOfJob(s.id); }, 100000);
        }

        function getListOfJob(s) {
            // AJAX
            var site = s;
            for (var i = 0; i < list.length; i++) { 
                if(list[i] != site) {
                    document.getElementById(list[i]).style.backgroundColor = "transparent";
                    document.getElementById(list[i]).style.color = "white";
                } else {
                    document.getElementById(list[i]).style.backgroundColor = "white";
                    document.getElementById(list[i]).style.color = "black";
                }
            }

            var xml = new XMLHttpRequest();
            var method = "GET";
            var url = site + ".php?user_id=" + jid;
            var asynchronous = true;

            xml.open(method, url, asynchronous);
            xml.send();
            xml.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var data = JSON.parse(this.responseText);
                    var htmldata = document.getElementById('list_area');
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        // Retrieve the data using AJAX from SQL
                        let user_ID = data[i].user_ID;
                        let user_firstname = data[i].user_firstname;
                        let user_lastname = data[i].user_lastname;
                        let jobdesc = data[i].jobdesc;
                        let datetime_user_created = data[i].datetime_user_created;

                        html += "<div class='cntx'><div class='cnpc'>";
                        html += "</div>";

                        html += "<h3 class='cname'> Name: " + user_firstname + " " + user_lastname + "</h3>";
                        html += "<h5> Time: " + datetime_user_created + "</h5>";
                        html += "<h5> User ID: " + user_ID + "</h5>";
                        html += "<input type='hidden' class='jobseekerid' value='" + user_ID + "'>";

                        html += '<form action="view_profile.php" method="POST">';
                        // gagamitin ko lng to sa paglalagay sa application logs ng data
                        html += '<input type="hidden" name="different_user_userid" value="' + user_ID + '">';
                        html += '<input type="hidden" name="job_id" value="' + jid + '">';
                        html += '<input type="hidden" name="different_user_type" value="employee">';
                        html += '<button class="btnview" type="submit">View Profile</button></form>';

                        // View details Button Form
                        html += '<div class="btns">';
                        // html += '<button class="btn" type="submit">View Profile</button></form>';
                        if(site!="waiting_list"){
                            html += '<form id="btn_waitingList" action="status_changer.php" method="POST">';
                            // gagamitin ko lng to sa paglalagay sa application logs ng data
                            html += '<input type="hidden" name="user_id" value="' + user_ID + '">';
                            html += '<input type="hidden" name="job_id" value="' + jid + '">';
                            // eto naman pang transaction logs(mala notif na)
                            html += '<input type="hidden" name="status" value="Waiting List">';
                            html += '<button class="btn" type="submit">Waiting List</button></form>';
                        }
                        
                        if(site!="short_listed"){
                            short_listedfrm = '<form style="display:flex; flex-direction:column; justify-content:center; align-items:center;" action="status_changer.php" method="POST">';
                            short_listedfrm += '<input type="hidden" name="user_id" id="user_id" >';
                            short_listedfrm += '<input type="hidden" name="job_id" value="' + jid + '">';
                            short_listedfrm += '<textarea id="expectations" style="height:600px; width:900px;" name="message" rows="3" required></textarea>';
                            short_listedfrm += '<input type="hidden" name="status" value="Short Listed">';
                            short_listedfrm += '<button class="btn" type="submit">Short Listed</button>';
                            html += '<button onclick="gotShort_listedfrm(this)" class="btn" type="submit">Short Listed</button></form>';
                        }

                        // Edit Button Form
                        if(site!="rejected"){
                            rejectedfrm = '<form style="display:flex; flex-direction:column; justify-content:center; align-items:center;" action="status_changer.php" method="POST">';
                            rejectedfrm += '<input type="hidden" name="user_id" id="user_id" >';
                            rejectedfrm += '<input type="hidden" name="job_id" id="job_id"  value="' + jid + '">';
                            rejectedfrm += '<textarea id="expectations" style="height:600px; width:900px;" name="message" rows="3" required></textarea>';
                            rejectedfrm += '<input type="hidden" name="status" value="Rejected">';
                            rejectedfrm += '<button class="btn" type="submit">Rejected</button></form>';
                            html += '<button onclick="gotRejected(this)" class="btn" type="submit">Rejected</button>';
                        }

                        var msg = 'Are you sure you want to accept this job?';
                        // Delete Button Form
                        if(site!="accepted"){
                            acceptedfrm = '<form style="display:flex; flex-direction:column; justify-content:center; align-items:center;" action="status_changer.php" method="POST">';
                            acceptedfrm += '<input type="hidden" name="user_id" id="user_id" >';
                            acceptedfrm += '<input type="hidden" name="job_id" id="job_id"  value="' + jid + '">';
                            acceptedfrm += '<textarea id="expectations" style="height:600px; width:900px;" name="message" rows="3" required></textarea>';
                            acceptedfrm += '<input type="hidden" name="status" value="Accepted">';
                            acceptedfrm += '<button class="btn" type="submit">Accepted</button></form>';
                            html += '<button onclick="gotAccepted(this)" class="btn" type="submit">Accepted</button>';
                        }
                        
                        html += "</div></div>";
                        
                    }
                    html += "</div>";
                    htmldata.innerHTML = html;
                }
            }

        }
        function gotRejected(button){
            var form_for_reason = document.getElementById('form_for_reason');
            document.getElementById('reasonform').innerHTML = rejectedfrm;
            form_for_reason.style.display = "block";
            var datatype_userid = button.closest('.cntx').querySelector('.jobseekerid').value;
            document.getElementById("user_id").value = datatype_userid;
        }
        function gotShort_listedfrm(button){
            var form_for_reason = document.getElementById('form_for_reason');
            document.getElementById('reasonform').innerHTML = short_listedfrm;
            form_for_reason.style.display = "block";
            var datatype_userid = button.closest('.cntx').querySelector('.jobseekerid').value;
            document.getElementById("user_id").value = datatype_userid;
        }
        function gotAccepted(button)
        {
            var form_for_reason = document.getElementById('form_for_reason');
            document.getElementById('reasonform').innerHTML = acceptedfrm;
            form_for_reason.style.display = "block";
            var datatype_userid = button.closest('.cntx').querySelector('.jobseekerid').value;
            document.getElementById("user_id").value = datatype_userid;
        }
        function form_exit(){
            var form_for_reason = document.getElementById('form_for_reason');
            form_for_reason.style.display = "none";
        }
    </script>
</body>
</html>
