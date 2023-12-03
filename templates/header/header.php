<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <style>
        /* CSS for the header */
        .header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .menu {
            list-style: none;
            padding: 0;
        }

        .menu li {
            display: inline;
            margin-right: 20px;
        }

        .logout {
            float: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <ul class="menu">
            <?php
            if (isset($_SESSION['is_company']) && $_SESSION['is_company']) {
                // Display company-specific menu items
                echo '<li><a href="arbeidsgiver_side.php">Hjem</a></li>';
                echo '<li><a href="arbeidsgiver_view_users.php">View Users</a></li>';
                echo '<li><a href="arbeidsgiver_nyapplication.php">Create New Job Application</a></li>';
                echo '<li><a href="arbeidsgiver_view_users.php">View Job Applications</a></li>';
            } elseif (isset($_SESSION['is_user']) && $_SESSION['is_user']) {
                // Display regular user menu items
                echo '<li><a href="bruker_side.php">Hjem</a></li>';
                echo '<li><a href="bruker_info.php">Profil</a></li>';
            } else {
                // Display default menu items for users not logged in
                echo '<li><a href="/index.php">Hjem</a></li>';
                echo '<li><a href="/login.php">Logg inn</a></li>';
            }
            ?>
        </ul>

        <?php
        // Display logout link if a user is logged in
        if (isset($_SESSION['user'])) {
            echo '<div class="logout">';
            echo '<a href="/logout.php">Logg ut</a>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- The rest of your page's content goes here -->
</body>
</html>
