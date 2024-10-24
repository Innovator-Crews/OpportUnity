<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .backbtn{
            height:10%;
            width: 90%;
            display:flex;
            align-items:center;
            font-weight: bold;
            font-size:25px;
        }
        form{
            height:90%;
            width: 100%;
            display:flex;
            flex-direction:column;
            justify-content:space-around;
            align-items:center;
        }
        .signupfrm{
            height:300px;
            width: 400px;
            backdrop-filter: blur(10px);
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            border-radius:10px;
            box-shadow:0px 0px 5px black;
        }
        .btnspc{
            height:90px;
            width: 300px;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        body{
            height:100vh;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            background:url("lp.jpg");
            background-size:cover;
        }
        a{
            text-decoration:none;
            color:black;
        }
        .gendiv{
            display:flex;
            justify-content:space-between;
            align-items:center;
            width: 60%;
        }
        .inputs{
            width: 250px;
            height: 40px;
            border-radius:5px;
            border:2px solid white;
        }
        .btn{
            width: 250px;
            height: 40px;
            border-radius:5px;
            background-color:Transparent;
            transition:0.5s;
            border:2px solid white;
        }
        .btn:hover{
            background-color:white;
        }
    </style>
</head>
<body>
<div class="signupfrm">
    <span class="backbtn">
            <a href="opportUnity.html">←</a>    
        </span> 
        <form action="opportUnity_mainpage.php" id="myForm" method="POST">
            <input type="hidden" id="userID" name="userID">
            <input class="inputs" type="text" id="uname" placeholder="Username" name="uname"><br/>
            <input class="inputs" type="password" id="pword" placeholder="Username" name="pword"><br/>
        </form>
    <button class="btn" type="button" onclick="submitForm()">LOGIN</button>
    <div id="asd"></div>
    <h3 id="invalid"></h3>
</div>

</body>
</html>
<?php
$conn = new mysqli("localhost", "root", "", "opportunity");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);

$u_s_e_r = [[], []];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $u_s_e_r[0][] = $row['user_username'];
        $u_s_e_r[1][] = $row['user_password'];
    }
}

// Convert PHP array to JSON
$u_s_e_r_json = json_encode($u_s_e_r);
?>

<script>
// Pass the PHP array to JavaScript
var userArray = <?php echo $u_s_e_r_json; ?>;

function submitForm() {
    let usernameMatched = false;
    let passwordMatched = false;

    const type_uname = document.getElementById('uname').value;
    userArray[0].forEach(function(usernm) {
        if (usernm === type_uname) {
            usernameMatched = true;
        }
    });

    const type_pword = document.getElementById('pword').value;
    userArray[1].forEach(function(userps) {
        if (userps === type_pword) {
            passwordMatched = true;
        }
    });

    if (usernameMatched && passwordMatched) {
        // Set the value of the hidden input field
        document.getElementById('userID').value = ''; // Example value

        // Submit the form
        document.getElementById('myForm').submit();
    } else {
        document.getElementById('invalid').innerHTML = "Invalid username or password";
    }
}
</script>
