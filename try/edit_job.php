<?php
// Start the session
session_start();
$userNAME = $_SESSION['username'] ?? null;
$userPASSWORD = $_SESSION['password'] ?? null;
$userID = $_SESSION['id'] ?? null;
$role = $_SESSION['usertype'] ?? null;
$conn = new mysqli("localhost", "root", "", "opportunity");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to update job post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobid = $_POST['id'];
    $company_name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_location = $_POST['job_location'];
    $salary = $_POST['salary'];
    $requirements = $_POST['requirements'];
    $qualities = $_POST['qualities'];
    $expectations = $_POST['expectations'];
    $job_type = $_POST['job_type'];
    $work_schedule_preference = $_POST['work_schedule_preference'];
    $company_industry_type = $_POST['company_industry_type'];

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job - OpportUnity</title>
    <link rel="stylesheet" href="opportUnity_postjob.css">
</head>
<body>

    <div id="postjob-container">
    <a href="opportUnity_dashboard_employer.php"><button class="headerbtn">Back to Homepage</button></a>
        <h2>Edit Job Post</h2>
        <form action="editJOB.php" id="postjob-form" method="POST">
            <div class="form-group">
                <label for="job-title">Job Title</label>
                <input type="text" id="job-title" value="<?= htmlspecialchars($job_title) ?>" name="job_title" required>
            </div>

            <div class="form-group">
                <label for="company-name">Company Name</label>
                <input type="text" id="company-name" value="<?= htmlspecialchars($company_name) ?>" name="company_name" required>
            </div>

            <div class="form-group">
                <label for="job-location">Location</label>
                <input type="text" id="job-location" value="<?= htmlspecialchars($job_location) ?>" name="job_location" required>
            </div>

            <div class="form-group">
                <label for="job-description">Job Description</label>
                <textarea id="job-description" name="job_description" rows="4" required><?= htmlspecialchars($job_description) ?></textarea>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="text" id="salary" value="<?= htmlspecialchars($salary) ?>" name="salary" required>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements" rows="3" required><?= htmlspecialchars($requirements) ?></textarea>
            </div>

            <div class="form-group">
                <label for="qualities">Qualities</label>
                <textarea id="qualities" name="qualities" rows="3" required><?= htmlspecialchars($qualities) ?></textarea>
            </div>

            <div class="form-group">
                <label for="expectations">Expectations</label>
                <textarea id="expectations" name="expectations" rows="3" required><?= htmlspecialchars($expectations) ?></textarea>
            </div>

            <!-- New added fields -->
            <div class="form-group">
                <label for="job-type">Job Type:</label>
                <select id="job-type" name="job_type">
                    <option value="full-time" <?= $job_type == 'full-time' ? 'selected' : '' ?>>Full-time</option>
                    <option value="part-time" <?= $job_type == 'part-time' ? 'selected' : '' ?>>Part-time</option>
                </select>
            </div>

            <div class="form-group">
                <label for="work_schedule_preference">Work Schedule Preference:</label>
                <select id="work_schedule_preference" name="work_schedule_preference">
                    <option value="on-site" <?= $work_schedule_preference == 'on-site' ? 'selected' : '' ?>>On-site</option>
                    <option value="online" <?= $work_schedule_preference == 'online' ? 'selected' : '' ?>>Online</option>
                    <option value="blended" <?= $work_schedule_preference == 'blended' ? 'selected' : '' ?>>Blended</option>
                </select>
            </div>

            <div class="form-group">
                <label for="company_industry_type">Company Industry Type:</label>
                <select id="company_industry_type" name="company_industry_type">
                    <option value="technology" <?= $company_industry_type == 'technology' ? 'selected' : '' ?>>Technology</option>
                    <option value="cooking" <?= $company_industry_type == 'cooking' ? 'selected' : '' ?>>Cooking</option>
                    <option value="travel" <?= $company_industry_type == 'travel' ? 'selected' : '' ?>>Travel</option>
                    <option value="thrill" <?= $company_industry_type == 'thrill' ? 'selected' : '' ?>>Thrill</option>
                    <option value="sports" <?= $company_industry_type == 'sports' ? 'selected' : '' ?>>Sports</option>
                </select>
            </div>

            <input type="hidden" name="job_id" value="<?= htmlspecialchars($jobid) ?>">
            <button name="makejobjob_title" class="submit-btn">Post Job</button>
        </form>
    </div>

</body>
</html>
