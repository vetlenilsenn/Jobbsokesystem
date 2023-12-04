<?php
require_once '../../database/tilkobling.php';

session_start(); // Start the session


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Code to retrieve the user based on the username
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id']; // Save the user_id in the session
        $_SESSION['user'] = $user['username']; // Save the username in the session

        // Determine user type and set session variables accordingly
        if ($user['is_company'] == 1) {
            $_SESSION['is_company'] = true;
            $_SESSION['is_user'] = false;
            // Retrieve the company_id and company_name for employers
            $queryCompany = "SELECT company_id, company_name FROM companies WHERE user_id = :user_id";
            $stmtCompany = $pdo->prepare($queryCompany);
            $stmtCompany->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
            $stmtCompany->execute();
            $company = $stmtCompany->fetch();

            if ($company) {
                $_SESSION['company_id'] = $company['company_id']; // Save the company_id in the session for employers
                $_SESSION['company_name'] = $company['company_name']; // Save the company_name in the session for employers
            }
        } else {
            $_SESSION['is_company'] = false;
            $_SESSION['is_user'] = true;
        }

        // Debugging statements
        var_dump($_SESSION);
        
        // Redirect based on user type
        if ($_SESSION['is_company']) {
            // This is an employer, redirect to the employer page
            header('Location: ../../arbeidsgivere/arbeidsgiver_side.php');
        } elseif ($_SESSION['is_user']) {
            // This is a job seeker, redirect to the existing protected page
            header('Location: ../../jobbsokere/sokjobb/bruker_side.php');
        }
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Feil brukernavn eller passord.";
    }
}
?>
<!-- The rest of your login form goes here -->

<!DOCTYPE html>
<html>
<head>
    <title>Innlogging</title>
    <meta charset="UTF-8">
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
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            margin-top: 15px;
            color: #555;
        }

        a {
            color: #1e90ff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
        
    <h2>Innlogging</h2>
    <form action="login.php" method="post" accept-charset="UTF-8">
        <label for="username">Brukernavn:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Passord:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Logg inn">
    </form>

    <p>Har du ikke en konto? <a href="../registrer/registration.php">Registrer deg her</a></p>
</body>
</html>
