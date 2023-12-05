<?php
if (isset($_GET['cv_path'])) {
    $cvPath = $_GET['cv_path'];
    header('Content-Type: application/pdf');
    readfile($cvPath);
} else {
    echo "CV kunne ikke hentes.";
}
