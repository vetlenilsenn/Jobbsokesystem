<?php
require_once '../database/tilkobling.php';
session_start();

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: login.php');
    exit();
}

// Fetch job applications for the current company
try {
    $companyId = $_SESSION['company_id'];

    $query = "SELECT * FROM job_applications WHERE company_id = :company_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->execute();
    $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching job applications: " . $e->getMessage());
}
?>

<!-- HTML content for displaying job applications -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Applications</title>
</head>
<body>
    <h2>Job Applications</h2>
    <ul>
        <?php foreach ($jobApplications as $job) : ?>
            <li>
                <strong><?php echo $job['job_title']; ?></strong>
                - <?php echo $job['job_description']; ?>
                - <?php echo $job['location']; ?>
                <!-- Add a button to view applicants for this job -->
                <form action="view_applicants.php" method="post">
                    <input type="hidden" name="job_application_id" value="<?php echo $job['application_id']; ?>">
                    <input type="submit" value="View Applicants">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="arbeidsgiver_side.php">Tilbake til arbeidsgiverside</a></br>
    <a href="../login.php">Logg ut</a>
</body>
</html>
