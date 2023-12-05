<?php
require_once '../../database/tilkobling.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        //Skaffer applikasjons id fra form submission
        $applicationId = isset($_POST['job_application_id']) ? $_POST['job_application_id'] : null;

        //Validerer application id
        if ($applicationId === null) {
            throw new Exception('Invalid request. Application ID is missing.');
        }

        //Sletter fra recieved application table
        $receivedQuery = "DELETE FROM received_applications WHERE job_application_id = :application_id";
        $receivedStmt = $pdo->prepare($receivedQuery);
        $receivedStmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);

        //Kjørere slett funkksjonen 
        $receivedStmt->execute();

        //Sletter så fra job_applications
        $jobQuery = "DELETE FROM job_applications WHERE application_id = :application_id";
        $jobStmt = $pdo->prepare($jobQuery);
        $jobStmt->bindParam(':application_id', $applicationId, PDO::PARAM_INT);

        //Kjører slett funksjonen
        if ($jobStmt->execute()) {
            //Slettet suksessfull
            header('Location: arbeidsgiver_applications.php'); //Blir værende på siden
            exit();
        } else {
            throw new Exception('Feil under sletting av jobb applikasjon.');
        }
    } catch (Exception $e) {
        //Error håndtering
        echo 'Error: ' . $e->getMessage();
        exit();
    }
} else {
    //Blir på samme side men sender ut en feil melding
    header('Location: arbeidsgiver_applications.php');
    echo 'Feil under sletting.';
    exit();
}
?>
