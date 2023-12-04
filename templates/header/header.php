<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <style>
        /* CSS for the header */
        body {
            margin: 0; /* Remove default body margin */
            font-family: 'Arial', sans-serif; /* Add a generic font family */
        }

        .header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px; /* Add padding to the top and bottom */
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0; /* Remove default margin for the menu */
        }

        .menu li {
            display: inline;
            margin-right: 20px;
            padding: 5px; /* Add padding to the list items */
        }

        .menu li a {
            text-decoration: none;
            color: white;
        }

        .menu li a:hover {
            color: #ffcc00; /* Change link color on hover */
            cursor: pointer; /* Change cursor to pointer on hover */
        }

        .logout a {
            text-decoration: none;
            color: #ffcc00; /* Set logout link color */
            padding: 10px;
        }

        .logout a:hover {
            text-decoration: underline; /* Underline on hover for the logout link */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-container">
            <ul class="menu">
            <?php
            if (isset($_SESSION['is_company']) && $_SESSION['is_company']) {
                // Display company-specific menu items
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/arbeidsgiver_side.php">Hjem</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/se_brukere/arbeidsgiver_view_users.php">View Users</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/nyjobb/arbeidsgiver_nyapplication.php">Create New Job Application</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/se_applikasjoner/view_applicants.php">View Job Applications</a></li>';
            } elseif (isset($_SESSION['is_user']) && $_SESSION['is_user']) {
                // Display regular user menu items
                echo '<li><a href="/jobbsokesystem/jobbsokere/sokjobb/bruker_side.php">Hjem</a></li>';
                echo '<li><a href="/jobbsokesystem/jobbsokere/jobbsokerprofil/bruker_info.php">Profil</a></li>';
            } else {
                // Display default menu items for users not logged in
                echo '<li><a href="/jobbsokesystem/index.php">Hjem</a></li>';
                echo '<li><a href=/jobbsokesystem/reglog/login/login.php>Logg inn</a></li>';
            }
            ?>
            </ul>

            <div class="logout">
                <?php
                // Display logout link if a user is logged in
                if (isset($_SESSION['user'])) {
                    echo '<a href="/jobbsokesystem/reglog/login/logout.php">Logg ut</a>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- The rest of your page's content goes here -->
</body>
</html>



