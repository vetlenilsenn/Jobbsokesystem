<?php
require_once '../../database/tilkobling.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get the application_id from the form submission
        $applicationId = isset($_POST['job_application_id']) ? $_POST['job_application_id'] : null;

        // Validate application_id
        if ($applicationId === null) {
            throw new Exception('Invalid request. Application ID is missing.');
        }

        // Delete from the received_applications table first
        $receivedQuery = "DELETE FROM received_applications WHERE job_application_id = :application_id";
        $receivedStmt = $pdo->prepare($receivedQuery);
        $receivedStmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);

        // Execute the delete query for received_applications
        $receivedStmt->execute();

        // Now delete from the job_applications table
        $jobQuery = "DELETE FROM job_applications WHERE application_id = :application_id";
        $jobStmt = $pdo->prepare($jobQuery);
        $jobStmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);

        // Execute the delete query for job_applications
        if ($jobStmt->execute()) {
            // Job application deleted successfully
            header('Location: arbeidsgiver_applications.php'); // Redirect back to applications side
            exit();
        } else {
            throw new Exception('Feil under sletting av jobb applikasjon.');
        }
    } catch (Exception $e) {
        // Handle exceptions, log errors, or redirect to an error page
        echo 'Error: ' . $e->getMessage();
        exit();
    }
} else {
    // Redirect to an error page if accessed through GET or without necessary parameters
    header('Location: arbeidsgiver_applications.php');
    echo 'Feil under sletting.';
    exit();
}
?>
