<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../reglog/login/login.php');
    exit();
}
require_once '../../database/tilkobling.php';
include('../../templates/header/header.php');

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
        // Handle file upload
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            $cvTempPath = $_FILES['cv']['tmp_name'];
            $cvPath = '../uploads/cv_' . $userId . '_' . time() . '.pdf'; // Adjust the path and filename as needed

            // Move the uploaded file to the desired location
            if (move_uploaded_file($cvTempPath, $cvPath)) {
                // Insert into received_applications table
                try {
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
            } else {
                echo "Error moving uploaded CV file.";
            }
        } else {
            echo "CV file not uploaded or invalid.";
        }
    }
}

// Rest of the HTML content for displaying job application details and the form for CV and application letter
?>
<!-- HTML content for displaying job application details and the form for CV and application letter -->
<html>
<head>
    <title>Bruker Side Apply</title>
    <style>
        /* Style to set the map image width */
        .map-image {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Application Details</h1>
    <p><strong>Company Name:</strong> <?php echo $jobApplication['company_name']; ?></p>
    <p><strong>Job Title:</strong> <?php echo $jobApplication['job_title']; ?></p>
    <p><strong>Job Description:</strong> <?php echo $jobApplication['job_description']; ?></p>
    <p><strong>Job Category:</strong> <?php echo $jobApplication['job_category']; ?></p>
    <p><strong>Contact Person:</strong> <?php echo isset($jobApplication['contact_person']) ? $jobApplication['contact_person'] : 'N/A'; ?></p>

    <?php
    // Display the map for the job location using MapQuest if a location is specified
    if (!empty($jobApplication['location'])):
    ?>
        <img class="map-image" src="https://www.mapquestapi.com/staticmap/v5/map?key=btjIKc7BBgW3hVRGcw34hVn7YYYDioce&size=600,400&locations=<?php echo urlencode($jobApplication['location']); ?>" alt="Kartutsnitt">
    <?php else: ?>
        <p>No location specified for this job application.</p>
    <?php endif; ?>

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
</body>
</html>
