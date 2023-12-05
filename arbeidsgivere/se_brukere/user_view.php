<?php
session_start();
require_once '../../database/tilkobling.php';

//Sjekker om brukeren er logget in og er en arbeidsgiver
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
    exit();
}

//Henter bruker info basert på user id
try {
    $userId = $_GET['user_id'];

    $query = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userDetails) {
        echo "User not found.";
        exit();
    }
} catch (PDOException $e) {
    die("Det skjedde en feil under hentingen av bruker detaljer: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 20px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        div.user-details {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 15px;
            border-radius: 4px;
            text-align: left;
        }

        div.user-details strong {
            color: #333;
        }

        img {
            max-width: 200px;  
            height: auto;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        button {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h2>Bruker Detaljer</h2>
    <div class="user-details">

        <?php
        $profilePicturePath = $userDetails['profile_picture'];
        if (!empty($profilePicturePath)) {
            echo "<img src=\"$profilePicturePath\" alt=\"Profile Picture\">";
        }
        
        ?>
<br> 
        <strong>Brukernavn:</strong> <?php echo $userDetails['username']; ?><br>
        <strong>Email:</strong> <?php echo $userDetails['email']; ?><br>
        <strong>Fornavn:</strong> <?php echo $userDetails['name']; ?><br>
        <strong>Etternavn:</strong> <?php echo $userDetails['surname']; ?><br>
        <strong>Bruker Kategori:</strong> <?php echo $userDetails['user_category']; ?><br>

        <?php
        $cvPath = $userDetails['cv_path'];
        if (!empty($cvPath)) {
            echo "<a href=\"view_cv.php?cv_path=$cvPath\" target=\"_blank\"><button>Se CV</button></a>";
        }
        ?>
        
    </div>
</body>
</html>
