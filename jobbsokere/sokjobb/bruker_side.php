<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../reglog/login/login.php');
    exit();
}

require_once '../../database/tilkobling.php';

// Fetch unique job categories from the database
try {
    $categoryQuery = "SELECT DISTINCT job_category FROM job_applications";
    $categoryStmt = $pdo->query($categoryQuery);
    $jobCategories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error fetching job categories: " . $e->getMessage());
}

// Initialize $selectedCategory
$selectedCategory = '';

// Fetch job applications from the database based on the selected category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_category'])) {
    $selectedCategory = $_POST['selected_category'];
    
    try {
        if (!empty($selectedCategory)) {
            // Modify the query to include the deadline condition
            $query = "SELECT * FROM job_applications 
                      WHERE job_category = :job_category 
                      AND deadline >= CURDATE()";  // Include deadline condition
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':job_category', $selectedCategory, PDO::PARAM_STR);
        } else {
            // If "No Category" is selected, fetch all job applications with deadline not passed
            $query = "SELECT * FROM job_applications 
                      WHERE deadline >= CURDATE()";  // Include deadline condition
            $stmt = $pdo->query($query);
        }

        $stmt->execute();
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching job applications: " . $e->getMessage());
    }
} else {
    // Fetch all job applications initially with deadline not passed
    try {
        $allQuery = "SELECT * FROM job_applications 
                     WHERE deadline >= CURDATE()";  // Include deadline condition
        $allStmt = $pdo->query($allQuery);
        $jobApplications = $allStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching job applications: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bruker side</title>
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

        form {
            display: inline-block;
            margin-bottom: 20px;
        }

        label {
            margin-right: 8px;
            color: #555;
        }

        select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        .job-application-item {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 15px;
            border-radius: 4px;
            text-align: left;
        }

        .job-application-item strong {
            color: #333;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h1>Velkommen til brukersiden, <?php echo $_SESSION['user']; ?>!</h1>

    <h2>Jobbsøknader</h2>

    <!-- Dropdown menu for selecting job category -->
    <form action="" method="post">
        <label for="category">Velg en jobbkatogori:</label>
        <select name="selected_category" id="category">
            <option value="">Ingen kategori</option> <!-- Added "No Category" option -->
            <?php foreach ($jobCategories as $category) : ?>
                <option value="<?php echo $category; ?>" <?php echo ($category == $selectedCategory) ? 'selected' : ''; ?>><?php echo $category; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Søk">
    </form>

    <ul>
        <?php foreach ($jobApplications as $job) : ?>
            <li class="job-application-item">
                <strong><?php echo $job['job_title']; ?></strong>
                - <?php echo $job['job_description']; ?>
                <br>
                <strong>Søknadsfrist:</strong> <?php echo $job['deadline']; ?>
                <form action="bruker_side_apply.php" method="post">
                    <input type="hidden" name="job_id" value="<?php echo $job['application_id']; ?>">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <input type="submit" value="Se og søk">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
