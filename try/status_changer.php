<?php
    // Start the session
    //dito nichachange if waitinglist, short list, rejected, or accepted yung nasa application_logs
    // dito din nichachange if hiring or full na yung job
    session_start();
    $userNAME = $_SESSION['username'] ?? null;
    $userPASSWORD = $_SESSION['password'] ?? null;
    $userID = $_SESSION['id'] ?? null;
    $role = $_SESSION['usertype'] ?? null;
    require 'connection.php';
    $jobId = $_POST['job_id'];
    $userStatus = $_POST['status'];
    $userID = $_POST['user_id'];

    //checking how many applicants is already accepted
    $sql = "SELECT COUNT(*) AS accepted_count FROM application_logs WHERE user_status = 'Accepted' AND jobid = '".$jobId."';";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result); 
    $number_of_accepted = $data['accepted_count'];
    

    //checking the number of limit in job
    $sql = "SELECT job_limit FROM job WHERE jobid = '".$jobId."';";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result); 
    $limit_job_number = $data['job_limit'];



    $conn = new mysqli("localhost", "root", "", "opportunity");
    if($userStatus == "Short Listed") {
        $sql = "UPDATE application_logs SET user_status = 'Short Listed' WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);

        // eto yung magiinsert ng reason or message sa jobseeker side galing employer
        $message = $_POST['message'];
        $sql = "INSERT INTO transaction_logs (jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, employer_message)
        SELECT jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, '".$message."'
        FROM application_logs
        WHERE jobid = '".$jobId."' AND jobseeker_userid = '".$userID."';";
        $result = mysqli_query($conn, $sql);

        // eto naman yung maglalagay ng red sa notif icon kasi magiging 1 value ng user_notif sa database
        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);

        //checking how many applicants is already accepted
        $sql = "SELECT COUNT(*) AS accepted_count FROM application_logs WHERE user_status = 'Accepted' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result); 
        $number_of_accepted = $data['accepted_count'];

        if($number_of_accepted<$limit_job_number){
            $sql = "UPDATE job SET job_status = 'Hiring' WHERE jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
        }
        
    }
    else if($userStatus == "Rejected") {
// eto sana yung maglalagay ng rejected status sa job application ng user 
        $sql = "UPDATE application_logs SET user_status = 'Rejected' WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);





        //eto yung magdedelete sa application ng user after 3days
        // $sql = "CREATE EVENT delete_rejected_rows ON SCHEDULE EVERY 1 DAY DO DELETE FROM application_logs WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."' AND user_status = 'Rejected' AND TIMESTAMPDIFF(DAY, timestamp_column, NOW()) > 3;";
//         $sql = "DROP EVENT IF EXISTS delete_rejected_rows;
// CREATE EVENT delete_rejected_rows ON SCHEDULE EVERY 1 DAY DO DELETE FROM application_logs WHERE jobseeker_userid = 113606362 AND jobid = 339739272 AND user_status = 'Rejected' AND TIMESTAMPDIFF(DAY, datetime_apply, NOW()) > 1;";
//         $result = mysqli_query($conn, $sql);

        // eto yung magiinsert ng reason or message sa jobseeker side galing employer
        $message = $_POST['message'];
        $sql = "INSERT INTO transaction_logs (jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, employer_message)
        SELECT jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, '".$message."'
        FROM application_logs
        WHERE jobid = '".$jobId."' AND jobseeker_userid = '".$userID."';";
        $result = mysqli_query($conn, $sql);


        

        // eto yung magcacancel sa application ng user after rejection
        $sql = "DELETE FROM application_logs WHERE user_status = 'Rejected' AND jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);



        // eto naman yung maglalagay ng red sa notif icon kasi magiging 1 value ng user_notif sa database
        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);

        $sql = "SELECT COUNT(*) AS accepted_count FROM application_logs WHERE user_status = 'Accepted' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result); 
        $number_of_accepted = $data['accepted_count'];

        if($number_of_accepted<$limit_job_number){
            $sql = "UPDATE job SET job_status = 'Hiring' WHERE jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
        }
        
    }    
    else if($userStatus == "Waiting List") {
        $sql = "UPDATE application_logs SET user_status = 'Waiting List' WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);

        $sql = "INSERT INTO transaction_logs (jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status)
        SELECT jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status
        FROM application_logs
        WHERE jobid = '".$jobId."' AND jobseeker_userid = '".$userID."';";
        $result = mysqli_query($conn, $sql);

        // eto naman yung maglalagay ng red sa notif icon kasi magiging 1 value ng user_notif sa database
        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);

        $sql = "SELECT COUNT(*) AS accepted_count FROM application_logs WHERE user_status = 'Accepted' AND jobid = '".$jobId."';";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result); 
        $number_of_accepted = $data['accepted_count'];

        if($number_of_accepted<$limit_job_number){
            $sql = "UPDATE job SET job_status = 'Hiring' WHERE jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
        }
        
    }
    else{
        // kapag ang job is hindi pa papuno
        if($number_of_accepted<$limit_job_number-1){
            if($userStatus == "Accepted") $sql = "UPDATE application_logs SET user_status = 'Accepted' WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
// eto yung magiinsert ng reason or message sa jobseeker side galing employer
            $message = $_POST['message'];
            $sql = "INSERT INTO transaction_logs (jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, employer_message)
            SELECT jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, '".$message."'
            FROM application_logs
            WHERE jobid = '".$jobId."' AND jobseeker_userid = '".$userID."';";
            $result = mysqli_query($conn, $sql);

        // eto naman yung maglalagay ng red sa notif icon kasi magiging 1 value ng user_notif sa database    // 
        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);


            $sql = "UPDATE job SET job_status = 'Hiring' WHERE jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
            
        }
//      kapag ang job ay one insert nalang is puno na
        else if($number_of_accepted==$limit_job_number-1){
            if($userStatus == "Accepted") $sql = "UPDATE application_logs SET user_status = 'Accepted' WHERE jobseeker_userid = '".$userID."' AND jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
// eto yung magiinsert ng reason or message sa jobseeker side galing employer
            $message = $_POST['message'];
            $sql = "INSERT INTO transaction_logs (jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, employer_message)
            SELECT jobid, employer_userid, job_position, company_name, first_name, last_name, jobsdec, jobseeker_userid, user_status, '".$message."'
            FROM application_logs
            WHERE jobid = '".$jobId."' AND jobseeker_userid = '".$userID."';";
            $result = mysqli_query($conn, $sql);

        // eto naman yung maglalagay ng red sa notif icon kasi magiging 1 value ng user_notif sa database    // 
        $sql = "UPDATE user SET user_notification = 1 WHERE user_type = 'employee'";
        $result = mysqli_query($conn, $sql);

            
            $sql = "UPDATE job SET job_status = 'Full' WHERE jobid = '".$jobId."';";
            $result = mysqli_query($conn, $sql);
        }
        // kapag puno na job
        else{
            $_SESSION['message'] = 'Job capacity is full';
            header('Location: view_job.php');
            exit();
        }
    }
    
    



    // $result = mysqli_query($conn, $sql);
    header('Location: view_job.php');
    exit();
?>