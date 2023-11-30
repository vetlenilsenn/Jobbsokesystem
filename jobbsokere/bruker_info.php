<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Include your database connection file (adjust the path accordingly)
require_once '../database/tilkobling.php';

// Fetch user information
try {
    $userId = $_SESSION['user_id'];

    $query = "SELECT username, name, surname, email, profile_picture FROM users WHERE user_id = :user_id";
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
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $pictureTempPath = $_FILES['profile_picture']['tmp_name'];
        $picturePath = 'profile_pictures/' . $userId . '_' . time() . '_' . $_FILES['profile_picture']['name'];

        // Move the uploaded file to the desired location
        if (move_uploaded_file($pictureTempPath, $picturePath)) {
            // Update user profile picture path in the database
            try {
                $updatePictureQuery = "UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id";
                $updatePictureStmt = $pdo->prepare($updatePictureQuery);
                $updatePictureStmt->bindParam(':profile_picture', $picturePath, PDO::PARAM_STR);
                $updatePictureStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

                if ($updatePictureStmt->execute()) {
                    echo "Profile picture updated successfully.";
                    // Update the $userInfo variable to reflect the new picture path
                    $userInfo['profile_picture'] = $picturePath;
                } else {
                    echo "Error updating profile picture path in the database.";
                }
            } catch (PDOException $e) {
                die("Error updating profile picture path: " . $e->getMessage());
            }
        } else {
            echo "Error moving uploaded profile picture file.";
        }
    }

    // Handle other form submissions (name, surname, email, password)
    // ... (as in your existing code)
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

    <!-- Display current profile picture -->
    <?php if (!empty($userInfo['profile_picture'])) : ?>
        <img src="<?php echo $userInfo['profile_picture']; ?>" alt="Profile Picture" style="max-width: 200px;">
    <?php endif; ?>

    <form action="bruker_info.php" method="post" enctype="multipart/form-data">
        <!-- Add input for profile picture -->
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*">
        <br>

        <label for="new_name">Name:</label>
        <input type="text" id="new_name" name="new_name" value="<?php echo $userInfo['name']; ?>">
        <br>

        <label for="new_surname">Surname:</label>
        <input type="text" id="new_surname" name="new_surname" value="<?php echo $userInfo['surname']; ?>">
        <br>

        <label for="new_email">Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $userInfo['email']; ?>">
        <br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password">
        <br>

        <input type="submit" value="Update Information">
    </form>

    <p>Vær obs på at endringer som er gjort etter innsendt søknad kan føre til at inkorrekt info står i søknaden!</p>

    <a href="bruker_side.php">Tilbake til brukersiden</a> </br>
    <a href="../login.php">Logg ut</a>
</body>
</html>
