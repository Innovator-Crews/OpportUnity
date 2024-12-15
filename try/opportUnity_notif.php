<?php
    // supposedly ito ay pang individual user notif lang, ibigsabihin if natuloy ko token_get_all
    // dapat yung notif ng user is about kanya at sa acc nya lang




    // Start the session
    session_start();

    // Global variables
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;
    $usertype = $_SESSION['usertype'] ?? null;


    // POST variables
    $date_time_user_notifClicked = $_POST['date_time_user_notifClicked'];


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
        }
    } 
    // If $_SESSION['usertype'] is set
    else if (isset($usertype)) {
        // Check if the usertype is employee
        if ($usertype == "employee") {
            // Redirect back to the employer dashboard after successful deletion
            header("Location: opportUnity_dashboard_jobseeker.php");
            exit;
        } else {
            // Redirect to login if usertype is not employee
            session_abort();
            include 'opportUnity_login.php';
            exit();
        }
    } else {
        session_abort();
        include 'opportUnity_login.php';
        exit();
    }
    
    //di pala dapat user tong select * from
    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user WHERE user_ID = ? AND date_time_user_notifClicked BETWEEN ? AND NOW();";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $userID, $date_time_user_notifClicked);
    $stmt->execute();
    $result = $stmt->get_result();

    
?>