<?php
//Kode for å inkludere header - Burde finne en bedre måte å inkludere på så man slipper å skrive om på hver side
include '../templates/header/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Jobbannonser</title>
</head>
<body>
    <h1>Ledige stillinger</h1>
    <form method="post" action="">
        <label for="job_search">Søk stillinger:</label>
        <input type="text" name="job_search" id="job_search">
        <input type="submit" value="Søk">
    </form>
    <ul>
        <?php
        //En array for jobbannonser, i det ferdige prosjektet vil jobbannonsense være hentet fra server
        $jobbannonser = [
            ["Stilling" => "Webutvikler", "Firma" => "ABC Web Solutions", "Sted" => "Oslo"],
            ["Stilling" => "Grafisk designer", "Firma" => "DesignPro", "Sted" => "Bergen"],
            ["Stilling" => "Markedsføringsansvarlig", "Firma" => "MarketingCo", "Sted" => "Trondheim"],
        ];

        if (isset($_POST['job_search'])) {
            $search_term = $_POST['job_search'];
            //Søkefunksjon for å kunne søke på spesifik jobb. Enten sted, firma eller stilling. Senere kan det være mulig å legge til tags
            //f.eks søke etter IT, økonomi, osv... for å få jobber relatert til det
            $filtered_job_ads = array_filter($jobbannonser, function ($ad) use ($search_term) {
                return stripos($ad['Stilling'], $search_term) !== false;
            });
        } else {
            $filtered_job_ads = $jobbannonser;
        }

        //Kode for å skrive ut hver annonse, bruker filtered_job_ads for å alltid finne de annonsene som matcher i søkefeltet
        //På koden under tildeleses hver annonse i filtered_job_ads en key(Id) med nummer. Denne tas med i lenken og brukes i sokejobb
        foreach ($filtered_job_ads as $key => $annonse) {
            echo "<li><strong>Stilling:</strong> " . $annonse['Stilling'] . "<br><strong>Firma:</strong> " . $annonse['Firma'] . "<br><strong>Sted:</strong> " . $annonse['Sted'] . "<br>";
            echo '<a href="..\jobbsokere\sokjobb\sokejobb.php?id=' . $key . '">Søk på denne jobben</a>';
            echo "</li><br><br>";
        //lenken må skrives om slik at den fungerer selv når siden blir inkludert i index
        }
        ?>
    </ul>
</body>
</html>
