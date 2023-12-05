<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../reglog/login/login.php');
    exit();
}

require_once '../../database/tilkobling.php';

//Henter bruker informasjon
try {
    $userId = $_SESSION['user_id'];

    $query = "SELECT username, name, surname, email, searchable, user_category, cv_path, profile_picture FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userInfo) {
        echo "Kunne ikke finne bruker.";
        exit();
    }
} catch (PDOException $e) {
    die("Det skjedde en feil under hentingen av bruker info: " . $e->getMessage());
}

//Prossessere form submissionen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Håndterer fil opplastning
    $cvPath = $userInfo['cv_path'];
    $profilePicturePath = $userInfo['profile_picture'];

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        // Handle CV file upload
        $cvTempPath = $_FILES['cv']['tmp_name'];
        $cvPath = '../../uploads/cv_' . $userId . '_' . time() . '.pdf';
        move_uploaded_file($cvTempPath, $cvPath);
    }

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        //Håndtererer bilde opplastning
        $profilePictureTempPath = $_FILES['profile_picture']['tmp_name'];
        $profilePicturePath = '../../uploads/profile_picture_' . $userId . '_' . time() . '.jpg'; 
        move_uploaded_file($profilePictureTempPath, $profilePicturePath);
    }

    //Oppdater bruker info
    try {
        //Oppdaterer searchable og bruker kateogri
        $searchable = isset($_POST['searchable']) ? 1 : 0;
        $userCategory = isset($_POST['user_category']) ? $_POST['user_category'] : $userInfo['user_category'];

        $updateQuery = "UPDATE users 
                        SET searchable = :searchable, 
                            user_category = :user_category, 
                            cv_path = :cv_path, 
                            profile_picture = :profile_picture 
                        WHERE user_id = :user_id";

        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':searchable', $searchable, PDO::PARAM_INT);
        $updateStmt->bindParam(':user_category', $userCategory, PDO::PARAM_STR);
        $updateStmt->bindParam(':cv_path', $cvPath, PDO::PARAM_STR);
        $updateStmt->bindParam(':profile_picture', $profilePicturePath, PDO::PARAM_STR);
        $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo "Bruker info oppdatert.";
        } else {
            echo "Det skjedde en feil under oppdateringen av bruker info.";
        }
    } catch (PDOException $e) {
        die("Det skjedde en feil under oppdateringen av bruker info: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bruker Informasjon</title>
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

        p {
            color: #555;
            margin: 10px 0;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="checkbox"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .profile-picture {
            max-width: 100px;
            height: auto;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
            padding: 10px;
            border: none;
            border-radius: 4px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .cv-button {
            display: inline-block;
            margin-left: 10px;
            background-color: #3498db;
            color: #fff;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
        }

        .cv-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h2>Bruker informasjon</h2>
    <p><strong>Brukernavn:</strong> <?php echo $userInfo['username']; ?></p>

    <?php
    //Skriver ut bildet om det er et
    $profilePicturePath = $userInfo['profile_picture'];
    if (!empty($profilePicturePath)) {
        echo "<img class='profile-picture' src=\"$profilePicturePath\" alt=\"Profile Picture\">";
    }
    ?>

    <form action="bruker_info.php" method="post" enctype="multipart/form-data">
        <label for="new_name">Fornavn:</label>
        <input type="text" id="new_name" name="new_name" value="<?php echo $userInfo['name']; ?>">

        <label for="new_surname">Etternavn:</label>
        <input type="text" id="new_surname" name="new_surname" value="<?php echo $userInfo['surname']; ?>">

        <label for="new_email">Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $userInfo['email']; ?>">

        <label for="searchable">Søkbar for arbeidsgivere:</label>
        <input type="checkbox" id="searchable" name="searchable" <?php echo $userInfo['searchable'] ? 'checked' : ''; ?>>

        <label for="user_category">Hovederfaringsområde:</label>
        <input type="text" id="user_category" name="user_category" value="<?php echo $userInfo['user_category']; ?>">

        <label for="cv"><?php echo empty($userInfo['cv_path']) ? 'Last opp CV:' : 'Endre CV:'; ?></label>
        <input type="file" id="cv" name="cv">

        <label for="profile_picture"><?php echo empty($userInfo['profile_picture']) ? 'Last opp profilbilde:' : 'Endre profilbilde:'; ?></label>
        <input type="file" id="profile_picture" name="profile_picture">

        <label for="new_password">Nytt passord:</label>
        <input type="password" id="new_password" name="new_password">

        <input type="submit" value="Oppdater Informasjon">

        <?php
        //Cv knapp
        $cvPath = $userInfo['cv_path'];
        if (!empty($cvPath)) {
            echo "<a class='cv-button' href=\"view_cv.php?cv_path=$cvPath\" target=\"_blank\">Se CV</a>";
        }
        ?>
    </form>

    <p> 
        Vær obs på at endringer som er gjort etter innsendt søknad kan føre til at inkorrekt info
        står i søknaden!
    </p>
</body>
</html>
