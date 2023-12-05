<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobbsøk</title>
    <style>
        body {
            margin: 0; 
            font-family: 'Arial', sans-serif; 
        }

        .header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0; 
        }

        .menu li {
            display: inline;
            margin-right: 20px;
            padding: 5px; 
        }

        .menu li a {
            text-decoration: none;
            color: white;
        }

        .menu li a:hover {
            color: #ffcc00; 
            cursor: pointer; 
        }

        .logout a {
            text-decoration: none;
            color: #ffcc00; 
            padding: 10px;
        }

        .logout a:hover {
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-container">
            <ul class="menu">
            <?php
            if (isset($_SESSION['is_company']) && $_SESSION['is_company']) {
                //Info som viser for bedriftsbruker
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/arbeidsgiver_side.php">Hjem</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/se_brukere/arbeidsgiver_view_users.php">Se Brukere</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/nyjobb/arbeidsgiver_nyapplication.php">Oprett Ny Jobb Applikasjon</a></li>';
                echo '<li><a href="/jobbsokesystem/arbeidsgivere/se_applikasjoner/arbeidsgiver_applications.php">Se Jobb Applikasjoner</a></li>';
            } elseif (isset($_SESSION['is_user']) && $_SESSION['is_user']) {
                //Info som kommer for vanlig bruker
                echo '<li><a href="/jobbsokesystem/jobbsokere/sokjobb/bruker_side.php">Hjem</a></li>';
                echo '<li><a href="/jobbsokesystem/jobbsokere/jobbsokerprofil/bruker_info.php">Profil</a></li>';
            } else {
                //Vises når du ikke er logget inn
                echo '<li><a href="/jobbsokesystem/index.php">Hjem</a></li>';
                echo '<li><a href=/jobbsokesystem/reglog/login/login.php>Logg inn</a></li>';
            }
            ?>
            </ul>

            <div class="logout">
                <?php
                //Hvis man er logget inn vises log ut
                if (isset($_SESSION['user'])) {
                    echo '<a href="/jobbsokesystem/reglog/login/logout.php">Logg ut</a>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>



