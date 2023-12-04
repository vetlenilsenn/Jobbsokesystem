<?php
//Kode for å inkludere header - Burde finne en bedre måte å inkludere på så man slipper å skrive om på hver side
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
        //En array for jobbannonser, i det ferdige prosjektet vil jobbannonsense være hentet fra server
    $jobbannonser = [
        ["Stilling" => "Webutvikler", "Firma" => "ABC Web Solutions", "Sted" => "Oslo"],
        ["Stilling" => "Grafisk designer", "Firma" => "DesignPro", "Sted" => "Bergen"],
        ["Stilling" => "Markedsføringsansvarlig", "Firma" => "MarketingCo", "Sted" => "Trondheim"],
    ];

    //En sjekk for å påsé at id er korrekt, om den er satt, er numerisk, større eller lik null og lavere enn antall jobbannonser(siden id starter på null)
    //Id sendes fra annonse siden basert på hvilken jobbannonse man trykker på
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] >= 0 && $_GET['id'] < count($jobbannonser)) {
        $job_id = $_GET['id'];
        $job = $jobbannonser[$job_id];

        echo "<p><strong>Stilling:</strong> " . $job['Stilling'] . "</p>";
        echo "<p><strong>Firma:</strong> " . $job['Firma'] . "</p>";
        echo "<p><strong>Sted:</strong> " . $job['Sted'] . "</p>";

        //En metode å sende inn søknad på, funksjonalitet er ikke innført enda
        //På det ferdige prosjektet vil dette sendes inn til server for lagring
        echo '<h2>Søk på denne jobben</h2>';
        echo '<form method="post" action="send-soknad.php">';
        
        //Linjen under brukes slik at når søker sender inn søknad så lagres det hvilken jobb de søker på
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
        //I tilfelle det er feil med jobbid vil denne istedet kjøre
        echo "<p>Ugyldig jobbid.</p>";
    }
    ?>
</body>
</html>
