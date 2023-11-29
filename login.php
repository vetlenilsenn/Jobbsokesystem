<?php
require_once 'database/tilkobling.php';

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
    
        if ($user['is_company'] == 1) {
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
    
            // This is an employer, redirect to the employer page
            header('Location: arbeidsgiver_side.php');
            exit(); // Ensure that no further code is executed after the redirect
        } else {
            // This is a job seeker, redirect to the existing protected page
            header('Location: bruker_side.php');
            exit(); // Ensure that no further code is executed after the redirect
        }
    } else {
        echo "Feil brukernavn eller passord.";
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Innlogging</title>
    <li><a href="Index8.php">Tilbake til Index8</a></li> 
    <meta charset="UTF-8">
</head>
<body>
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

    <p>Har du ikke en konto? <a href="registration.html">Registrer deg her</a></p>
</body>
</html>
