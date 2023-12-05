<?php
require_once '../../database/tilkobling.php';

session_start();

//Sjekk om brukeren er logget inn og er en arbeidsgiver
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
    exit();
}

//Hent jobbsøknader for gjeldende selskap
try {
    $companyId = $_SESSION['company_id'];

    $query = "SELECT * FROM job_applications WHERE company_id = :company_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->execute();
    $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Det skjedde en feil under hentingen av applikasjoner: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobb Applikasjoner</title>
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

        .job-application {
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            padding: 20px;
            text-align: left;
        }

        .job-application strong {
            color: #333;
        }

        .job-application form {
            display: inline-block;
            margin-right: 10px; 
        }

        .job-application .view-btn input[type="submit"] {
            background-color: #4caf50; 
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .job-application .view-btn input[type="submit"]:hover {
            background-color: #45a049;
        }

        .job-application .delete-btn input[type="submit"] {
            background-color: #ff4d4d; 
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .job-application .delete-btn input[type="submit"]:hover {
            background-color: #ff3333;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h2>Jobb Applikasjoner</h2>
    <ul>
        <?php foreach ($jobApplications as $job) : ?>
            <li class="job-application">
                <strong><?php echo $job['job_title']; ?></strong>
                - <?php echo $job['job_description']; ?>
                - <?php echo $job['location']; ?>
                
                <form action="view_applicants.php" method="post" class="view-btn">
                    <input type="hidden" name="job_application_id" value="<?php echo $job['application_id']; ?>">
                    <input type="submit" value="Se Applikanter">
                </form>
                
                <form action="delete_job.php" method="post" class="delete-btn">
                    <input type="hidden" name="job_application_id" value="<?php echo $job['application_id']; ?>">
                    <input type="submit" value="Slett Annonse">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
