<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cvPath = isset($_POST['cv_path']) ? $_POST['cv_path'] : null;

    if (!empty($cvPath)) {
        header('Content-Type: application/pdf'); 
        readfile($cvPath);
        exit();
    } else {
        echo "Ugyldig forespørsel.";
    }
} else {
    echo "Ugyldig forespørsel.";
}
?>
