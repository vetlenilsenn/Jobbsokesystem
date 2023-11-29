<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Include your database connection file (adjust the path accordingly)
require_once 'database/tilkobling.php';

// Fetch job application details
try {
    $jobId = isset($_POST['job_id']) ? $_POST['job_id'] : null;
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if ($jobId === null || $userId === null) {
        echo "Invalid request.";
        exit();
    }

    $query = "SELECT * FROM job_applications WHERE application_id = :job_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    $jobApplication = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jobApplication) {
        echo "Job application not found.";
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching job application details: " . $e->getMessage());
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if letter_text is set and not empty
    $letterText = isset($_POST['letter_text']) ? $_POST['letter_text'] : null;

    if (!empty($letterText)) {
        // Insert into received_applications table
        try {
            $cvPath = 'path_to_cv.pdf'; // Example path to CV

            $insertQuery = "INSERT INTO received_applications (job_application_id, user_id, cv_path, letter_text, date_applied) 
                            VALUES (:job_application_id, :user_id, :cv_path, :letter_text, NOW())";
            $insertStmt = $pdo->prepare($insertQuery);
            $insertStmt->bindParam(':job_application_id', $jobId, PDO::PARAM_INT);
            $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $insertStmt->bindParam(':cv_path', $cvPath, PDO::PARAM_STR);
            $insertStmt->bindParam(':letter_text', $letterText, PDO::PARAM_STR);

            if ($insertStmt->execute()) {
                echo "Application submitted successfully.";
            } else {
                echo "Error submitting application.";
            }
        } catch (PDOException $e) {
            die("Error processing application: " . $e->getMessage());
        }
    }
}

// Rest of the HTML content for displaying job application details and the form for CV and application letter
?>
<!-- HTML content for displaying job application details and the form for CV and application letter -->
<html>
<head>
    <title>Bruker Side Apply</title>
</head>
<body>
    <h1>Application Details</h1>
    <p><strong>Company Name:</strong> <?php echo $jobApplication['company_name']; ?></p>
    <p><strong>Job Title:</strong> <?php echo $jobApplication['job_title']; ?></p>
    <p><strong>Job Description:</strong> <?php echo $jobApplication['job_description']; ?></p>
    <p><strong>Job Category:</strong> <?php echo $jobApplication['job_category']; ?></p>
    <p><strong>Contact Person:</strong> <?php echo isset($jobApplication['contact_person']) ? $jobApplication['contact_person'] : 'N/A'; ?></p>

    <h2>Submit Your Application</h2>
    <form action="bruker_side_apply.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $jobId; ?>">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

        <label for="cv">Upload CV:</label>
        <input type="file" name="cv" accept=".pdf" required>
        
        <label for="letter_text">Application Letter:</label>
        <textarea name="letter_text" required></textarea>

        <input type="submit" value="Submit Application">
    </form>

    <a href="index.php">Logg ut</a>
</body>
</html>
