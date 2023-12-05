<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Jobbsøknader</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f8f8;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
    </style>
</head>

<body>
    <?php include('templates/header/header.php'); ?>
    <h2>Jobbsøknader</h2>

    <?php
    //Database connection
    include('database/tilkobling.php');

    try {
        //Henter jobb applikasjoner sin data med "company" detaljer og filterer ut basert på deadlines
        $stmt = $pdo->query("
            SELECT 
                ja.job_title,
                c.company_name,
                ja.job_description,
                ja.job_category,
                ja.deadline
            FROM job_applications ja
            JOIN companies c ON ja.company_id = c.company_id
            WHERE ja.deadline >= CURDATE()
        ");
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //Displayer dataen i tabbelen
        if ($jobApplications) {
            echo '<table>';
            echo '<tr><th>Tittel</th><th>Bedrift</th><th>Jobb beskrivelse</th><th>Jobb kategori</th><th>Søknadsfrist</th></tr>';
            foreach ($jobApplications as $application) {
                echo '<tr>';
                echo '<td>' . $application['job_title'] . '</td>';
                echo '<td>' . $application['company_name'] . '</td>';
                echo '<td>' . $application['job_description'] . '</td>';
                echo '<td>' . $application['job_category'] . '</td>';
                echo '<td>' . $application['deadline'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'Ingen jobb applikasjoner funnet.';
        }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>

</body>
</html>
