<?php
require_once '../../database/tilkobling.php';

session_start();

//Sjekker om brukeren er logget inn og er et selskap
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
    exit();
}

//Funksjon for å lage ny jobb applikasjon
function createJobApplication($userId, $companyId, $jobTitle, $jobDescription, $jobCategory, $location, $deadline) {
    global $pdo;

    //Henter company navn fra sessiosen
    $companyName = $_SESSION['company_name'];

    $query = "INSERT INTO job_applications (user_id, company_id, job_title, job_description, job_category, company_name, location, deadline) 
            VALUES (:user_id, :company_id, :job_title, :job_description, :job_category, :company_name, :location, :deadline)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->bindParam(':job_title', $jobTitle, PDO::PARAM_STR);
    $stmt->bindParam(':job_description', $jobDescription, PDO::PARAM_STR);
    $stmt->bindParam(':job_category', $jobCategory, PDO::PARAM_STR);
    $stmt->bindParam(':company_name', $companyName, PDO::PARAM_STR);
    $stmt->bindParam(':location', $location, PDO::PARAM_STR);
    $stmt->bindParam(':deadline', $deadline, PDO::PARAM_STR);

    return $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oprett Jobb Applikasjon</title>
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
        textarea,
        input[type="date"] {
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
    <h2>Opprett Jobb Applikasjon</h2>
    <?php
    //Sjekker om formen er postet
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $jobTitle = filter_input(INPUT_POST, 'job_title', FILTER_SANITIZE_STRING);
        $jobDescription = filter_input(INPUT_POST, 'job_description', FILTER_SANITIZE_STRING);
        $jobCategory = filter_input(INPUT_POST, 'job_category', FILTER_SANITIZE_STRING);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
        $deadline = $_POST['deadline']; 

        //Opprett jobb applikasjoner
        if (createJobApplication($_SESSION['user_id'], $_SESSION['company_id'], $jobTitle, $jobDescription, $jobCategory, $location, $deadline)) {
            echo "Jobb applikasjon er opprettet.";
        } else {
            echo "Det skjedde en feil under opprettingen.";
        }
    }
    ?>

    <form action="arbeidsgiver_nyapplication.php" method="post" accept-charset="UTF-8">
        <label for="job_title">Jobb Tittel:</label>
        <input type="text" id="job_title" name="job_title" required>

        <label for="job_description">Jobb Beskrivelse:</label>
        <textarea id="job_description" name="job_description" required></textarea>

        <label for="job_category">Jobb Kategori:</label>
        <input type="text" id="job_category" name="job_category" required>

        <label for="location">Lokasjon:</label>
        <input type="text" id="location" name="location" required>

        <label for="deadline">Søknadsfrist:</label>
        <input type="date" id="deadline" name="deadline" required>

        <input type="submit" value="Opprett Jobb Applikasjon">
    </form>
</body>
</html>
