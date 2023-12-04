<?php
// view_cv.php


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cvPath = isset($_POST['cv_path']) ? $_POST['cv_path'] : null;

    if (!empty($cvPath)) {
        // You may want to add security measures before displaying the file, such as checking file existence, file type, etc.
        header('Content-Type: application/pdf'); // Assuming PDF file, adjust as needed
        readfile($cvPath);
        exit();
    } else {
        echo "Invalid request.";
    }
} else {
    echo "Invalid request.";
}
?>
