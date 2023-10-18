<?php
// Include the header
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
        // Dette er en eksempelarray med jobbannonser
        $jobbannonser = [
            ["Stilling" => "Webutvikler", "Firma" => "ABC Web Solutions", "Sted" => "Oslo"],
            ["Stilling" => "Grafisk designer", "Firma" => "DesignPro", "Sted" => "Bergen"],
            ["Stilling" => "Markedsføringsansvarlig", "Firma" => "MarketingCo", "Sted" => "Trondheim"],
        ];

        if (isset($_POST['job_search'])) {
            $search_term = $_POST['job_search'];
            // Filtrer jobbannonser basert på søket
            $filtered_job_ads = array_filter($jobbannonser, function ($ad) use ($search_term) {
                return stripos($ad['Stilling'], $search_term) !== false;
            });
        } else {
            $filtered_job_ads = $jobbannonser;
        }

        // Gå gjennom annonsene og vis dem på nettsiden
        foreach ($filtered_job_ads as $key => $annonse) {
            echo "<li><strong>Stilling:</strong> " . $annonse['Stilling'] . "<br><strong>Firma:</strong> " . $annonse['Firma'] . "<br><strong>Sted:</strong> " . $annonse['Sted'] . "<br>";
            echo '<a href="..\jobbsokere\sokjobb\sokejobb.php?id=' . $key . '">Søk på denne jobben</a>';
            echo "</li><br><br>";
        }
        ?>
    </ul>
</body>
</html>
