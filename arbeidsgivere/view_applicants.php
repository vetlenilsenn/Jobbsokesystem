<?php
require_once '../database/tilkobling.php';
session_start();

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: login.php');
    exit();
}

// Check if the job_application_id is set
if (!isset($_POST['job_application_id'])) {
    echo "Invalid request.";
    exit();
}

$jobApplicationId = $_POST['job_application_id'];

// Fetch applicants for the selected job application with user details
try {
    $query = "SELECT ra.*, u.name, u.surname FROM received_applications ra
              JOIN users u ON ra.user_id = u.user_id
              WHERE ra.job_application_id = :job_application_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_application_id', $jobApplicationId, PDO::PARAM_INT);
    $stmt->execute();
    $applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching applicants: " . $e->getMessage());
}
?>

<!-- HTML content for displaying applicants for the selected job application -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applikanter</title>
</head>
<body>
    <h2>Alle applikanter til jobben</h2>
    <ul>
        <?php foreach ($applicants as $applicant) : ?>
            <li>
                <strong>Navn:</strong> <?php echo $applicant['name'] . ' ' . $applicant['surname']; ?><br>
                <strong>CV:</strong> <?php echo $applicant['cv_path']; ?><br>
                <strong>Søknadsbrev:</strong> <?php echo $applicant['letter_text']; ?><br>
                <strong>Innsendt dato:</strong> <?php echo $applicant['date_applied']; ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="arbeidsgiver_applications.php">Tilbake til applikasjonsoversikt</a></br>
    <a href="../login.php">Logg ut</a>
</body>
</html>
