<?php
session_start();
include('../templates/header/header.php');

//Sjekker om brukeren er logget in og er en arbeidsgiver
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
        <p>Info side</p></br>
        <p2>Her ville vi lagt til en nyhetsside for oppdateringer og diverse info om systemet</p2>
    
    </ul>
</body>
</html>
