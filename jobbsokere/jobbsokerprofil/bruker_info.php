<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
include('../templates/header/header.php');
// Include your database connection file (adjust the path accordingly)
require_once '../database/tilkobling.php';

// Fetch user information
try {
    $userId = $_SESSION['user_id'];

    $query = "SELECT username, name, surname, email, searchable, user_category FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userInfo) {
        echo "User not found.";
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching user information: " . $e->getMessage());
}

// Process form submission for updating user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information
    try {
        // Update searchable
        $searchable = isset($_POST['searchable']) ? 1 : 0; // Convert to boolean
        $updateQuery = "UPDATE users SET searchable = :searchable WHERE user_id = :user_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':searchable', $searchable, PDO::PARAM_INT);
        $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            echo "Searchable status updated successfully.";
        } else {
            echo "Error updating searchable status.";
        }

        // Update user category
        $userCategory = isset($_POST['user_category']) ? $_POST['user_category'] : $userInfo['user_category'];
        $updateCategoryQuery = "UPDATE users SET user_category = :user_category WHERE user_id = :user_id";
        $updateCategoryStmt = $pdo->prepare($updateCategoryQuery);
        $updateCategoryStmt->bindParam(':user_category', $userCategory, PDO::PARAM_STR);
        $updateCategoryStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        if ($updateCategoryStmt->execute()) {
            echo "User category updated successfully.";
        } else {
            echo "Error updating user category.";
        }
    } catch (PDOException $e) {
        die("Error updating user information: " . $e->getMessage());
    }
}
?>

<!-- HTML content for bruker_info.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
</head>
<body>
    <h2>User Information</h2>
    <p><strong>Username:</strong> <?php echo $userInfo['username']; ?></p>

    <form action="bruker_info.php" method="post">
        <label for="new_name">Name:</label>
        <input type="text" id="new_name" name="new_name" value="<?php echo $userInfo['name']; ?>">
        <br>

        <label for="new_surname">Surname:</label>
        <input type="text" id="new_surname" name="new_surname" value="<?php echo $userInfo['surname']; ?>">
        <br>

        <label for="new_email">Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $userInfo['email']; ?>">
        <br>

        <label for="searchable">Searchable:</label>
        <input type="checkbox" id="searchable" name="searchable" <?php echo $userInfo['searchable'] ? 'checked' : ''; ?>>
        <br>

        <label for="user_category">Hovederfaringsområde:</label>
        <input type="text" id="user_category" name="user_category" value="<?php echo $userInfo['user_category']; ?>">
        <br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">
        <br>

        <input type="submit" value="Update Information">
    </form>
    <p> 
    Vær obs på at endringer som er gjort etter innsendt søknad kan føre til at inkorrekt info
    står i søknaden!
</p>
</body>
</html>
