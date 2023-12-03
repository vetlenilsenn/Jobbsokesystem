<?php
require_once 'database/tilkobling.php'; // Include the connection file

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
        echo "Error: Passwords do not match.";
        exit();
    }

    // Create user
    if (createUser($username, $password, $email, $isCompany, $name, $surname)) {
        echo "User created successfully.<br>";

        // If the user is a company, create a company with auto-generated contact_person
        if ($isCompany) {
            $userId = $pdo->lastInsertId(); // Get the user_id of the newly created user
            $companyName = $_POST['company_name'];

            if (createCompany($userId, $companyName, $name, $surname)) {
                echo "Company created successfully.";
            } else {
                echo "Error creating company.";
            }
        }
    } else {
        echo "Error creating user.";
    }
}
?>

<!-- HTML Form for User and Company Creation -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User and Company Creation</title>
</head>
<body>
    <h2>User and Company Creation</h2>
    <form action="registration.php" method="post" accept-charset="UTF-8">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <input type="checkbox" id="is_company" name="is_company" value="1">
        <label for="is_company">Is this a company?</label>
        <br>
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name">
        <br>
        <input type="submit" value="Create User and Company">
    </form>
    <p>Har du allerede en konto? <a href="login.php"> Logg inn her</a></p>
</body>
</html>
