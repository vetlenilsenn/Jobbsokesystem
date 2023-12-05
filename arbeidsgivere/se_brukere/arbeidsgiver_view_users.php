<?php

session_start();
require_once '../../database/tilkobling.php';




// Check if the user is logged in and is an employer
if (!isset($_SESSION['user']) || !$_SESSION['is_company']) {
    header('Location: ../../reglog/login/login.php');
    exit();
}


// Fetch unique user categories from the users table
try {
    $categoriesQuery = "SELECT DISTINCT user_category FROM users";
    $categoriesStmt = $pdo->query($categoriesQuery);
    $categories = $categoriesStmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Error fetching user categories: " . $e->getMessage());
}

// Fetch users with searchable set to true and based on selected user_category
try {
    $selectedCategory = isset($_POST['user_category']) ? $_POST['user_category'] : '';
    
    $query = "SELECT * FROM users WHERE searchable = 1";
    
    // If a specific user category is selected, add it to the query
    if (!empty($selectedCategory)) {
        $query .= " AND user_category = :user_category";
    }

    $stmt = $pdo->prepare($query);

    // Bind parameters if a specific user category is selected
    if (!empty($selectedCategory)) {
        $stmt->bindParam(':user_category', $selectedCategory, PDO::PARAM_STR);
    }

    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users by User Category</title>
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
            margin-bottom: 20px;
        }

        label {
            margin-right: 10px;
            color: #555;
        }

        select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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

        ul {
            list-style: none;
            padding: 0;
        }
        /* Be more specific with a class, assuming your targeted li has a class like 'user-item' */
        
        li.user-item {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 15px;
            border-radius: 4px;
            text-align: left;
        }

        li.user-item strong {
            color: #333;
        }

    </style>
</head>
<body>
    <?php include('../../templates/header/header.php'); ?>
    <h2>Filtrer basert på Bruker Kategori</h2>
    <form action="arbeidsgiver_view_users.php" method="post">
        <label for="user_category">Velg Bruker Kategori:</label>
        <select id="user_category" name="user_category">
            <option value="">Alle Kategorier</option>
            <?php foreach ($categories as $category) : ?>
                <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <h2>Brukere som har skrudd på søk</h2>
<ul>
    <?php foreach ($users as $user) : ?>
        <li class="user-item">
            <strong>Navn:</strong> <?php echo $user['name'] . ' ' . $user['surname']; ?><br>
            <strong>Bruker Kategori:</strong> <?php echo $user['user_category']; ?><br>
            <strong>Email:</strong> <?php echo $user['email']; ?><br>
            <a href="user_view.php?user_id=<?php echo $user['user_id']; ?>"><button>Se Bruker Detaljer</button></a><br>
            <!-- <a href=\"view_cv.php?cv_path=$cvPath\" target=\"_blank\"><button>View CV</button></a>" -->
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>
