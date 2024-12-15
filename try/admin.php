<?php
    // session_start();    
    // if(isset($_POST['username'])) $_SESSION['admin_username'] = $_POST['username'];
    // $uname = $_SESSION['admin_username'];
    // if(isset($_POST['password'])) $_SESSION['admin_password'] = $_POST['password'];
    // $pword = $_SESSION['admin_password'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" type="image/png" href="faviconlogo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spectral&display=swap" rel="stylesheet">
   
</head>
<body>
    <!-- since admin sya, naisip ko na di dapat nasa database yung acc nya -->
    
    <!-- Nav bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <img onclick="logout()" src="logo.png" alt="OpportUnity Logo" class="navbar-logo">
            <div onclick="logout()" class="logo">OpportUnity</div>
            <ul class="nav-links">
                <li><a href="opportUnity.html">Landing Page</a></li>
                <li><a href="#">Terms & Condition</a></li>
            </ul>
        </div>
        <div class="navbar-right">
            <div id="userMenu" class="user-menu">
                <span id="userName">Welcome, ADMIN</span>
                <div id="dropdown" class="dropdown-content">
                    <a href="profile.html">View Profile</a>
                    <a href="#" onclick="logout()">Logout</a>
                </div>
                <form method="POST">
                    <input type="hidden" value=" " name="username">
                    <input type="hidden" value=" " name="password">
                </form>
            </div>
            <a href="opportUnity_notification_list.php"><div id="user_notif" onclick="" style="width:40px; height:40px; border-radius:100%; background-size:cover; margin:0px 20px 0px 30px;"></div></a>
            
        </div>
    </nav>

    <h1 class="header">All Users</h1>
    <div class="container">
        <div id="applicantslist"></div>
    </div>

    <script>

        var currentUrl = window.location.href;
        

        function getApplicants() {
            var xml = new XMLHttpRequest(); 
            var method = "GET"; 
            var url = "admin_all_user.php?user_id=''"; 
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
                        var user_ID = data[i].user_ID;
                        var user_type = data[i].user_type;
                        var user_firstname = data[i].user_firstname;
                        var user_lastname = data[i].user_lastname;
                        var fullname = user_firstname + " " + user_lastname;
                        var user_username = data[i].user_username;
                        var user_sex = data[i].user_sex;
                        var datetime_user_created = data[i].datetime_user_created;
                        var date_of_birth = data[i].date_of_birth;
                        var age = data[i].age;
                        var use_phone_number = data[i].use_phone_number;
                        var user_last_login = data[i].user_last_login;
                        var AccountState = data[i].AccountState;

                        html += "<div class='cntx'><div class='cnpc'>";
                        html += "</div>";
                        html += "<h2> User ID: " + user_ID + "</h2>";
                        html += "<h3> Name: " + fullname + "</h3>";
                        html += "<h3 class='cname'> Role: " + user_type + "</h3>";
                        html += "<h3 class='cname'> Username: " + user_username + "</h3>";
                        html += "<input type='hidden' class='userid' value='" + user_ID + "'>";
                        html += '<div style="display:flex; justify-content:space-between; width:90%;">';
                        html += "<h5> Account created time: " + datetime_user_created + "</h5>";
                        html += "</div>";
                        html += "<h5> Last Logged: " + user_last_login + "</h5>";
                        html += "<h5> Account State: " + AccountState + "</h5>";

                        // Details Button Form
                        html += '<div style="display:flex; justify-content:space-between; width:90%;">';
                        html += '<form action="admin_control_activate.php" method="POST">';
                        html += '<input type="hidden" name="userid" value="'+user_ID+'">';
                        html += '<button class="btn" name="different_user_view_profile" type="submit">ACTIVE</button></form>';

                        var msg = 'Are you sure you want to make this user INACTIVE?';
                        // Decline Button Form
                        html += '<form action="admin_control_inactive.php" method="POST" onsubmit="return confirm(\'' + msg + '\')">';
                        html += '<input type="hidden" name="userid" value="'+user_ID+'">';
                        html += '<input type="hidden" name="currentUrl" value="' + currentUrl + '">';
                        html += '<button class="btn" type="submit">INACTIVE</button></form>';
                        
                        html += "</div></div>";
                    }
                    html += "</div>";
                    htmldata.innerHTML = html;
                }
            }
        }

        getApplicants();
        setInterval(getApplicants, 100000);
    </script>
</body>
</html>