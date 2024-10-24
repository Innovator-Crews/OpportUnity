<?php
    // include 'opportUnity_login.php';
    $userNAME = $_POST['uname']??null;
    $userPASSWORD = $_POST['pword']??null;
    $name;
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
                $name = $user['user_fullname'];
                $pic = "data:image/jpeg;base64,".base64_encode($user['profile_photo']);
            }
        }else{
            include 'opportUnity_login.php';
            exit();
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            padding:0px;
            margin:0px;
        }
        header{
            background-color:#0481fe;
            width:100%;
            height: 60px;
            display:flex;
            justify-content:space-between;
            font-size:30px;
        }
        span{
            display:flex;
            justify-content:center;
            align-items:center;
            color:#ffb900;
            width: 250px;
        }
        .nav-bar{
            display:flex;
            justify-content:center;
            align-items:center;
            color:#ffffff;
            font-size: 20px;
            width: 250px;
        }
        .prof{
            display:flex;
            align-items:center;
            width: 80px;
        }
        nav{
            display:flex;
            justify-content:center;
            align-items:center;
        }
        ul{
            display:flex;
            justify-content:space-evenly;
            align-items:center;
            font-size: 16px;
            width: 300px;
        }
        li{
            color:#ffffff;
            list-style-type:none;
            cursor:pointer;
        }
        a{
            text-decoration:none;
            color:#ffffff;
        }
        .cont{
            height:100vh;
            display:flex;
            flex-direction:column;
        }
        #dataSec{
            height:100%;
            width: 100%;
        }
        .cntx{
            height:200px;
            width: 500px;
            border-radius: 10px;
            background-color:#003c78;
            margin: 5px;
            color:white;
            display: flex;
            flex-direction:column;
            justify-content:space-evenly;
            padding-left: 10px;
        }
        img{
            height: 40px;;
            width: 40px;
            border-radius:50%;
        }
    </style>
</head>
<body>
    <div class="cont">
        <header>
            <span>
                <p>OpportUNITY</p>
            </span>
            <nav>
                <ul>
                    <li>Home</li>
                    <li>About</li>
                    <li>Contacts</li>
                    <a id="gotoLogout"><li>Logout</li></a>
                </ul>
                <div class="nav-bar">
                    <p><?=$name?></p>
                </div>
                <!--
                                                    <div class="prof">
                                                        <img src="=//$pic?>" alt="">
                                                    </div>
                -->
            </nav>
        </header>
        <div id='dataSec'></div>
    </div>
    <script>
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
        var url = "mainpage_context.php";
        var asynchronous = true;

        xml.open(method, url, asynchronous);
        xml.send();
        xml.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = JSON.parse(this.responseText);
                var htmldata = document.getElementById('dataSec');
                var html = '';
                for (var i = 0; i < data.length; i++) {
                    var jobid = data[i].jobid;
                    var companyname = data[i].companyname;
                    var jobname = data[i].jobname;
                    var jobdesc = data[i].jobdesc;
                                                //var joblogo = data[i].joblogo;

                    html += "<div class='cntx'><div class='cnpc'>";
                                                //html += "<img src='" + joblogo + "' width='100px' height='100px'/>";
                    html += "</div>";
                    html += "<h1> Company: " + companyname + "</h1>";
                    html += "<h3> Job position: " + jobname + "</h3>";
                    html += "<h5> Job Description: " + jobdesc + "</h5>";
                    html += "<h5> Job ID: " + jobid + "</h5>";
                    html += "</h1></div>";
                }
                htmldata.innerHTML = html;
            }
        }
    }
</script>

</body>
</html>