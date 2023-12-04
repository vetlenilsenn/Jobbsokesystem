<?php
require_once '../../database/tilkobling.php';

session_start();



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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>

    <h2>Create Job Application</h2>
    <form action="arbeidsgiver_nyapplication.php" method="post" accept-charset="UTF-8">
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" required>

        <label for="job_description">Job Description:</label>
        <textarea id="job_description" name="job_description" required></textarea>

        <label for="job_category">Job Category:</label>
        <input type="text" id="job_category" name="job_category" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <input type="submit" value="Create Job Application">
    </form>
</body>
</html>
