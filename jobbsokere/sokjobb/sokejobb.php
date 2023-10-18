<?php
// Include the header
include '../../templates/header/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Søk på jobb</title>
</head>
<body>
    <h1>Søk på jobben</h1>
    <?php
    // Dette er en eksempelarray med jobbannonser
    $jobbannonser = [
        ["Stilling" => "Webutvikler", "Firma" => "ABC Web Solutions", "Sted" => "Oslo"],
        ["Stilling" => "Grafisk designer", "Firma" => "DesignPro", "Sted" => "Bergen"],
        ["Stilling" => "Markedsføringsansvarlig", "Firma" => "MarketingCo", "Sted" => "Trondheim"],
    ];

    // Sjekk om "id" -parameteren er satt og gyldig
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 0 && $_GET['id'] < count($jobbannonser)) {
        $job_id = $_GET['id'];
        $job = $jobbannonser[$job_id];

        echo "<p><strong>Stilling:</strong> " . $job['Stilling'] . "</p>";
        echo "<p><strong>Firma:</strong> " . $job['Firma'] . "</p>";
        echo "<p><strong>Sted:</strong> " . $job['Sted'] . "</p>";

        // Legg til et skjema for å sende inn en søknad
        echo '<h2>Søk på denne jobben</h2>';
        echo '<form method="post" action="send-soknad.php">';
        echo '<input type="hidden" name="job_id" value="' . $job_id . '">';
        echo '<label for="navn">Navn:</label>';
        echo '<input type="text" name="navn" id="navn"><br>';
        echo '<label for="epost">E-post:</label>';
        echo '<input type="email" name="epost" id="epost"><br>';
        echo '<label for="soknadstekst">Søknadstekst:</label><br>';
        echo '<textarea name="soknadstekst" id="soknadstekst" rows="4" cols="50"></textarea><br>';
        echo '<input type="submit" value="Send søknad">';
        echo '</form>';
    } else {
        echo "<p>Ugyldig jobbid.</p>";
    }
    ?>
</body>
</html>
