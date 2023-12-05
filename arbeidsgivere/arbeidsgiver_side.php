<?php
session_start();
include('../templates/header/header.php');

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../reglog/login/login.php');
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
    <h1>Velkommen til arbeidsgiver siden, <?php echo $_SESSION['user']; ?>!</h1>

    <h2>Arbeidsgiver</h2>
    <ul>
        <li>Info side</li>
        <li>Navigasjonsbar</li>
    
    </ul>
</body>
</html>
