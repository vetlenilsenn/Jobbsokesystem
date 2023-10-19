<?php
//Kode for å inkludere header - Burde finne en bedre måte å inkludere på så man slipper å skrive om på hver side
include '../../templates/header/header.php';
?>
<html>
<head>
</head>
</html>
<?php
//Lager arrays for brukerprofil og for feilmeldinger. Eksempelet brukes som placeholder
//Dette skal også hentes fra server
$brukerprofil = array(
    'Navn' => 'Per',
    'Mobilnummer' => '99999999',
    'E-post' => 'test@eksempel.no'
);
$feilmeldinger = array();
//Her er det viktig at vi legger inn vasking av data - må se på dette
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Sjekker om navn er tomt
    if (empty($_POST["navn"])) {
        $feilmeldinger[] = "Navn er obligatorisk.";
    } else {
        $nytt_navn = $_POST["navn"];
        //Sjekker om navnet inneholder engelske bokstaver og mellomrom. -Dette fungerte ikke for foreleser.
        //Kanskje fordi han bruker ´ i navnet? Dette må vi fikse
        if (!preg_match("/^[a-zA-Z ]*$/", $nytt_navn)) {
            $feilmeldinger[] = "Navn kan kun inneholde bokstaver og mellomrom.";
        } else {
            //Oppdaterer derretter brukerprofil hvis navnet er gyldig
            $brukerprofil['Navn'] = $nytt_navn;
            echo "Navnet er oppdatert.";
        }
    }
//Her er det viktig at vi legger inn vasking av data - må se på dette
    //Sjekker om mobilnummer er tomt
    if (empty($_POST["mobilnummer"])) {
        $feilmeldinger[] = "Mobilnummer er obligatorisk.";
    } else {
        $nytt_mobilnummer = $_POST["mobilnummer"];
        //Sjekker om mobilnummeret er gyldig (8 siffer for Norskt mobilnummer)
        if (!preg_match("/^[0-9]{8}$/", $nytt_mobilnummer)) {
            $feilmeldinger[] = "Mobilnummeret er ikke gyldig.";
        } else {
            //Oppdater brukerprofil om mobilnummeret er gyldig
            $brukerprofil['Mobilnummer'] = $nytt_mobilnummer;
            echo "Mobilnummeret er oppdatert.";
        }
    }
//Her er det viktig at vi legger inn vasking av data - må se på dette
    //Sjekker om epost er tomt
    if (empty($_POST["epost"])) {
        $feilmeldinger[] = "E-post er obligatorisk.";
    } else {
        $ny_epost = $_POST["epost"];
        //Sjekker e-postadressen ved å bruke filter validate email
        if (!filter_var($ny_epost, FILTER_VALIDATE_EMAIL)) {
            $feilmeldinger[] = "E-postadressen er ikke gyldig.";
        } else {
            //Oppdater brukerprofil om e-postadressen er korrekt
            $brukerprofil['E-post'] = $ny_epost;
            echo "E-postadressen er oppdatert.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Brukerprofil</title>
</head>
<body>
    <h2>Brukerprofil</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        Navn: <input type="text" name="navn" value="<?php echo $brukerprofil['Navn']; ?>"><br>
        Mobilnummer: <input type="text" name="mobilnummer" value="<?php echo $brukerprofil['Mobilnummer']; ?>"><br>
        E-post: <input type="text" name="epost" value="<?php echo $brukerprofil['E-post']; ?>"><br>
        <input type="submit" name="submit" value="Lagre endringer">
    </form>
    <?php
    //Skriver ut feilmeldinger om det er noen
    if (!empty($feilmeldinger)) {
        echo "<h3>Feilmeldinger:</h3>";
        foreach ($feilmeldinger as $feilmelding) {
            echo $feilmelding . "<br>";
        }
    }
    ?>
</body>
</html>
