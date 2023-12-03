<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Include your database connection file (adjust the path accordingly)
require_once '../database/tilkobling.php';

// Fetch unique job categories from the database
try {
    $categoryQuery = "SELECT DISTINCT job_category FROM job_applications";
    $categoryStmt = $pdo->query($categoryQuery);
    $jobCategories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error fetching job categories: " . $e->getMessage());
}

// Fetch job applications from the database based on selected category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_category'])) {
    $selectedCategory = $_POST['selected_category'];
    
    try {
        $query = "SELECT * FROM job_applications WHERE job_category = :job_category";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':job_category', $selectedCategory, PDO::PARAM_STR);
        $stmt->execute();
        $jobApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching job applications: " . $e->getMessage());
    }
} else {
    // Fetch all job applications initially
    try {
        $allQuery = "SELECT * FROM job_applications";
        $allStmt = $pdo->query($allQuery);
        $jobApplications = $allStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching job applications: " . $e->getMessage());
    }
}
?>

<html>
<head>
    <title>Brukerside</title>
</head>
<body>
    <h1>Velkommen til brukersiden, <?php echo $_SESSION['user']; ?>!</h1>
    
    <h2>Job Applications</h2>

    <!-- Dropdown menu for selecting job category -->
    <form action="" method="post">
        <label for="category">Select Job Category:</label>
        <select name="selected_category" id="category">
            <?php foreach ($jobCategories as $category) : ?>
                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Search">
    </form>

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
    <a href="../logout.php">Logg ut</a>
</body>
</html>
