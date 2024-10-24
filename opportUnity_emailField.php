<?php
    
    // include 'opportUnity_login.php';
    $name;

    $conn = new mysqli("localhost", "root", "", "opportunity");
    $sql = "SELECT * FROM user";
    $result = mysqli_query($conn, $sql);

    $u_s_e_r = [[], [], []];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $u_s_e_r[0][] = $row['user_username'];
            $u_s_e_r[1][] = $row['user_password'];
        }
    }

    $request = $_GET["suggestt"];

    $suggest = "";

    // Lookup all suggestions from array if $request is different from ""
    if ($request !== "") {
        $request = strtolower($request);
        $len = strlen($request);
        foreach ($u_s_e_r[0] as $name) {    
            //if substr dont possess the value $name's value has it will be false
            //example 'macmac' yung laman ng $name tas yung value ng substr is 'er' walang nagequal so magfafalse
            //pag nag false, yung $name don naman sya sa next value
            if (stristr($name, substr($request, 0, $len))) {
                if ($suggest === "") {
                    if($request == $name)
                    {
                        $suggest = "opportUnity_mainpage.php";
                    }
                }
            }
        }
        
    }

    // Output "No suggestion" if no hint was found or output correct values
    echo $suggest === "" ? "No suggestion" : $suggest;

    $conn->close();
?>