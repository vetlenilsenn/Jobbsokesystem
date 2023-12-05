<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../reglog/login/login.php');
    exit();
}
require_once '../../database/tilkobling.php';

// Fetch job application details
try {
    $jobId = isset($_POST['job_id']) ? $_POST['job_id'] : null;
    $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    if ($jobId === null || $userId === null) {
        echo "Invalid request.";
        exit();
    }

    $query = "SELECT job_applications.*, users.name AS contact_person_name, users.surname AS contact_person_surname, users.email AS contact_person_email
              FROM job_applications 
              JOIN users ON job_applications.user_id = users.user_id
              WHERE job_applications.application_id = :job_id";
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


// Rest of the HTML content for displaying job application details and the form for CV and application letter
?>
<!-- HTML content for displaying job application details and the form for CV and application letter -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bruker Side Apply</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 20px;
            text-align: center;
        }

        h1, h2 {
            color: #333;
        }

        p {
            color: #555;
            margin: 10px 0;
        }

        .map-image {
            max-width: 100%;
            height: auto;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); 
    // Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if letter_text is set and not empty
    $letterText = isset($_POST['letter_text']) ? $_POST['letter_text'] : null;

    if (!empty($letterText)) {
        // Handle file upload
        if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
            // Validate file type and size
            $allowedFileTypes = ['application/pdf'];
            $maxFileSize = 5 * 1024 * 1024; // 5 MB

            if (!in_array($_FILES['cv']['type'], $allowedFileTypes) || $_FILES['cv']['size'] > $maxFileSize) {
                echo "Invalid file type or size.";
                exit();
            }

            $cvTempPath = $_FILES['cv']['tmp_name'];
            $cvPath = '../../uploads/cv_' . $userId . '_' . time() . '.pdf'; // Adjust the path and filename as needed

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
                        echo "Søknaden er sendt inn.";
                    } else {
                        echo "Det skjedde en feil under innsendigen.";
                    }
                } catch (PDOException $e) {
                    die("Det skjedde en feil under innsendigen: " . $e->getMessage());
                }
            } else {
                echo "Feil under CV håndtering.";
            }
        } else {
            echo "CV filen ble ikke lastet opp eller er ugyldig.";
        }
    }
}
?>
    <h1>Application Details</h1>
    <p><strong>Selskapets Navn:</strong> <?php echo $jobApplication['company_name']; ?></p>
    <p><strong>Jobb Tittel:</strong> <?php echo $jobApplication['job_title']; ?></p>
    <p><strong>Jobb Beskrivelse:</strong> <?php echo $jobApplication['job_description']; ?></p>
    <p><strong>Jobb Kategori:</strong> <?php echo $jobApplication['job_category']; ?></p>
    <p><strong>Kontakt Person:</strong> <?php echo isset($jobApplication['contact_person_name']) ? $jobApplication['contact_person_name'] . ' ' . $jobApplication['contact_person_surname'] : 'N/A'; ?></p>
    <p><strong>Kontakt Email:</strong> <?php echo isset($jobApplication['contact_person_email']) ? $jobApplication['contact_person_email'] : 'N/A'; ?></p>

    <?php if (!empty($jobApplication['location'])): ?>
        <img class="map-image" src="https://www.mapquestapi.com/staticmap/v5/map?key=btjIKc7BBgW3hVRGcw34hVn7YYYDioce&size=600,400&locations=<?php echo urlencode($jobApplication['location']); ?>" alt="Kartutsnitt">
    <?php else: ?>
        <p>Ingen lokasjoner spesifisert for denne jobben.</p>
    <?php endif; ?>

    <h2>Send inn din søknad</h2>
    <form action="bruker_side_apply.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="job_id" value="<?php echo $jobId; ?>">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

        <label for="cv">Last oppp CV:</label>
        <input type="file" name="cv" accept=".pdf" required>
        
        <label for="letter_text">Søknadsbrev:</label>
        <textarea name="letter_text" required></textarea>

        <input type="submit" value="Send inn søknad">
    </form>
</body>
</html>
