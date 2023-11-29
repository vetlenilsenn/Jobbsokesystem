<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Job Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>


<body>
    <?php include('templates/header/header.php'); ?>
    <h2>Job Applications</h2>

    <?php
     
    // Include your database connection script here using PDO
    include('database/tilkobling.php'); // Adjust the path as needed

    try {
        // Fetch job applications data with company details
        $stmt = $pdo->query("
            SELECT 
                ja.job_title,
                c.company_name,
                ja.job_description,
                ja.job_category
            FROM job_applications ja
            JOIN companies c ON ja.company_id = c.company_id
        ");
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the data in a table
        if ($jobApplications) {
            echo '<table>';
            echo '<tr><th>Job Title</th><th>Company Name</th><th>Job Description</th><th>Job Category</th></tr>';
            foreach ($jobApplications as $application) {
                echo '<tr>';
                echo '<td>' . $application['job_title'] . '</td>';
                echo '<td>' . $application['company_name'] . '</td>';
                echo '<td>' . $application['job_description'] . '</td>';
                echo '<td>' . $application['job_category'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'No job applications found.';
        }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>

</body>
</html>
