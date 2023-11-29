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
    <h2>Job Applications</h2>

    <?php
    // Include your database connection script here using PDO
    include('database/tilkobling.php');  // Include the connection file

    try {
        // Fetch job applications data
        $stmt = $pdo->query("SELECT * FROM job_applications");
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Display the data in a table
        if ($jobApplications) {
            echo '<table>';
            echo '<tr><th>Application ID</th><th>User ID</th><th>Company ID</th><th>Job Title</th></tr>';
            foreach ($jobApplications as $application) {
                echo '<tr>';
                echo '<td>' . $application['application_id'] . '</td>';
                echo '<td>' . $application['user_id'] . '</td>';
                echo '<td>' . $application['company_id'] . '</td>';
                echo '<td>' . $application['job_title'] . '</td>';
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
