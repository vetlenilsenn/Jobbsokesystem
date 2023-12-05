<?php
require_once '../../database/tilkobling.php';

session_start();



// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applikanter</title>
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

        ul {
            list-style-type: none;
            padding: 0;
        }

        .applicant-li {
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            padding: 20px;
            text-align: left;
        }

        .applicant-li strong {
            color: #333;
        }

        .applicant-li form {
            display: inline-block;
            margin-top: 10px;
        }

        .applicant-li button {
            background-color: #4caf50;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .applicant-li button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h2>Alle applikanter til jobben</h2>
    <ul>
        <?php foreach ($applicants as $applicant) : ?>
            <li class="applicant-li">
                <strong>Navn:</strong> <?php echo $applicant['name'] . ' ' . $applicant['surname']; ?><br>
                <!-- Replace CV display with a button to view CV in a new page tab -->
                <form action="view_cv.php" method="post" target="_blank">
                    <input type="hidden" name="cv_path" value="<?php echo $applicant['cv_path']; ?>">
                    <button type="submit">Se CV</button>
                </form>
                <strong>Søknadsbrev:</strong> <?php echo $applicant['letter_text']; ?><br>
                <strong>Innsendt dato:</strong> <?php echo $applicant['date_applied']; ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
