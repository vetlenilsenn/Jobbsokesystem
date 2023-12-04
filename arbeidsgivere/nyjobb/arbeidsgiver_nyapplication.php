<?php
require_once '../../database/tilkobling.php';

session_start();
include('../../templates/header/header.php');


// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
    exit();
}

// Function to create a new job application
function createJobApplication($userId, $companyId, $jobTitle, $jobDescription, $jobCategory, $location) {
    global $pdo;

    // Retrieve company name from the session
    $companyName = $_SESSION['company_name'];

    $query = "INSERT INTO job_applications (user_id, company_id, job_title, job_description, job_category, company_name, location) 
            VALUES (:user_id, :company_id, :job_title, :job_description, :job_category, :company_name, :location)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->bindParam(':job_title', $jobTitle, PDO::PARAM_STR);
    $stmt->bindParam(':job_description', $jobDescription, PDO::PARAM_STR);
    $stmt->bindParam(':job_category', $jobCategory, PDO::PARAM_STR);
    $stmt->bindParam(':company_name', $companyName, PDO::PARAM_STR);
    $stmt->bindParam(':location', $location, PDO::PARAM_STR);

    return $stmt->execute();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = $_POST['job_title'];
    $jobDescription = $_POST['job_description'];
    $jobCategory = $_POST['job_category'];
    $location = $_POST['location']; // Added location field

    // Create job application
    if (createJobApplication($_SESSION['user_id'], $_SESSION['company_id'], $jobTitle, $jobDescription, $jobCategory, $location)) {
        echo "Job application created successfully.";
    } else {
        echo "Error creating job application.";
    }
}
?>

<!-- HTML Form for Job Application Creation -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job Application</title>
</head>
<body>
    <h2>Create Job Application</h2>
    <form action="arbeidsgiver_nyapplication.php" method="post" accept-charset="UTF-8">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required>
        <br>
        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" required></textarea>
        <br>
        <label for="job_category">Job Category:</label>
        <input type="text" id="job_category" name="job_category" required>
        <br>
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>
        <br>
        <input type="submit" value="Create Job Application">
    </form>
</body>
</html>
