<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Include your database connection file (adjust the path accordingly)
require_once '../database/tilkobling.php';

// Fetch job applications from the database
try {
    $query = "SELECT * FROM job_applications";
    $stmt = $pdo->query($query);
    $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching job applications: " . $e->getMessage());
}
?>

<html>
<head>
    <title>Brukerside</title>
</head>
<body>
    <h1>Velkommen til brukersiden, <?php echo $_SESSION['user']; ?>!</h1>
    
    <h2>Job Applications</h2>
    <ul>
        <?php foreach ($jobApplications as $job) : ?>
            <li>
                <strong><?php echo $job['job_title']; ?></strong>
                - <?php echo $job['job_description']; ?>
<!-- Change the form method back to POST -->
<form action="bruker_side_apply.php" method="post">
    <input type="hidden" name="job_id" value="<?php echo $job['application_id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    <!-- Remove the letter_text field -->
    <input type="submit" value="View and Apply">
</form>

            </li>
        <?php endforeach; ?>
    </ul>
    <a href="bruker_info.php">Profil</a> </br>
    <a href="../login.php">Logg ut</a>
</body>
</html>
