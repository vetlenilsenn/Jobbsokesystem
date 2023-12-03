<?php
session_start();
include('../templates/header/header.php');

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbeidsgiver Side</title>
</head>
<body>
    <h1>Welcome to the Employer's Side, <?php echo $_SESSION['user']; ?>!</h1>

    <h2>Employer Options</h2>
    <ul>
        <li><a href="arbeidsgiver_view_users.php">View users</a></li>
        <li><a href="arbeidsgiver_nyapplication.php">Create New Job Application</a></li>
        <li><a href="arbeidsgiver_applications.php">View Job Applications</a></li>
    </ul>
</body>
</html>
