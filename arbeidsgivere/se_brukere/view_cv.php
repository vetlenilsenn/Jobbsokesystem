<?php
if (isset($_GET['cv_path'])) {
    $cvPath = $_GET['cv_path'];
    // Add appropriate headers for PDF files
    header('Content-Type: application/pdf');
    readfile($cvPath);
} else {
    echo "CV not found.";
}
