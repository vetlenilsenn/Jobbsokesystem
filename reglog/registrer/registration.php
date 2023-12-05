<?php
require_once '../../database/tilkobling.php';


// Function to create a new user
function createUser($username, $password, $email, $isCompany, $name, $surname) {
    global $pdo;

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password, email, is_company, name, surname) VALUES (:username, :password, :email, :is_company, :name, :surname)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':is_company', $isCompany, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);

    return $stmt->execute();
}

// Function to create a new company with auto-generated contact_person
function createCompany($userId, $companyName, $name, $surname) {
    global $pdo;

    // Auto-generate contact_person based on name and surname
    $contactPerson = $name . ' ' . $surname;

    $query = "INSERT INTO companies (user_id, company_name, contact_person) VALUES (:user_id, :company_name, :contact_person)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':company_name', $companyName, PDO::PARAM_STR);
    $stmt->bindParam(':contact_person', $contactPerson, PDO::PARAM_STR);

    return $stmt->execute();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $email = $_POST['email'];
    $isCompany = isset($_POST['is_company']) ? 1 : 0;
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    // Validate that passwords match
    if ($password !== $confirmPassword) {
        echo "Error: Passord er ikke like.";
        exit();
    }

    // Create user
    if (createUser($username, $password, $email, $isCompany, $name, $surname)) {
        echo "Bruker opprettelse vellykket.<br>";

        // If the user is a company, create a company with auto-generated contact_person
        if ($isCompany) {
            $userId = $pdo->lastInsertId(); // Get the user_id of the newly created user
            $companyName = $_POST['company_name'];

            if (createCompany($userId, $companyName, $name, $surname)) {
                echo "Bedrift opprettelse vellykket.";
            } else {
                echo "Feil ved opprettelse av Bedrift.";
            }
        }
    } else {
        echo "Feil ved opprettelse av bruker.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bruker og bedrift registrering</title>
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

        input, select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="checkbox"] {
            margin-right: 5px;
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
    
    <h2>Bruker og bedrift registrering</h2>
    <form action="registration.php" method="post" accept-charset="UTF-8">
        <label for="name">Navn:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="surname">Etternavn:</label>
        <input type="text" id="surname" name="surname" required>
        <br>
        <label for="username">Brukernavn:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Passord:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Bekreft Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <input type="checkbox" id="is_company" name="is_company" value="1">
        <label for="is_company">Er dette en bedrift?</label>
        <br>
        <label for="company_name">Bedrift navn:</label>
        <input type="text" id="company_name" name="company_name">
        <br>
        <input type="submit" value="Lag bruker og bedrift">
    </form>
    <p>Har du allerede en konto? <a href="../login/login.php"> Logg inn her</a></p>
</body>
</html>
