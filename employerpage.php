<form method="POST" enctype="multipart/form-data">
    <label for="fname">Company Name:</label>
    <input type="text" name="cname" id="fname"><br/>
    <label for="uname">Job position:</label>
    <input type="text" name="jpos" id="uname"><br/>
    <label for="pword">Job Description:</label>
    <input type="text" name="jdes" id="pword"><br/>
    <label for="pword">Company Logo:</label>
    <input type="file" name="clogo" id="imahe"><br/>
    <input type="submit" name="submit">
</form>
<a href="opportUnity.html"><button>Back to Landing Page</button></a>

<?php

if (isset($_POST['submit'])) {
    $compname = $_POST['cname'];
    $jobpos = $_POST['jpos'];
    $jobdes = $_POST['jdes'];
    $complogo = file_get_contents($_FILES['clogo']['tmp_name']); // Read the file content

    $user_id = 0; // Initialize user_id

    $conn = new mysqli("localhost", "root", "", "opportunity");

    if ($conn) {
        do {
            $user_id = rand(100000000, 999999999);
            $sql = "SELECT * FROM job WHERE jobid='$user_id'";
            $result = mysqli_query($conn, $sql);
        } while (mysqli_num_rows($result) > 0);

        $sql = "INSERT INTO job (jobid, companyname, jobname, jobdesc, joblogo) VALUES ('$user_id', '$compname', '$jobpos', '$jobdes', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("b", $complogo); // Bind the BLOB data

        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Connection failed: " . mysqli_connect_error();
    }
}
?>

<script>
    // Check if the page was just reloaded
    if (performance.navigation.type === performance.navigation.TYPE_RELOAD) {
        window.location.replace("employerpage.php");
    }
</script>
