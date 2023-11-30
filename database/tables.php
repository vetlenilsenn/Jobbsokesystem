<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'job_search_system');

// Create PDO connection
$dsn = 'mysql:host=' . DB_HOST;
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Create the database and switch to it
$sqlCreateDB = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
try {
    $pdo->exec($sqlCreateDB);
    echo "Database created successfully\n";
    $pdo->exec("USE " . DB_NAME);
} catch (PDOException $e) {
    die("Error creating or switching to the database: " . $e->getMessage());
}

// Create users table
$sqlCreateUsersTable = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    is_company BOOLEAN NOT NULL
)";
try {
    $pdo->exec($sqlCreateUsersTable);
    echo "Users table created successfully\n";
} catch (PDOException $e) {
    die("Error creating the users table: " . $e->getMessage());
}

// Create companies table
$sqlCreateCompaniesTable = "CREATE TABLE IF NOT EXISTS companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    company_name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";
try {
    $pdo->exec($sqlCreateCompaniesTable);
    echo "Companies table created successfully\n";
} catch (PDOException $e) {
    die("Error creating the companies table: " . $e->getMessage());
}

// Create job_applications table
$sqlCreateJobApplicationsTable = "CREATE TABLE IF NOT EXISTS job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    company_id INT,
    job_title VARCHAR(100) NOT NULL,
    company_name VARCHAR(100) NOT NULL, -- Add company_name field
    job_description TEXT NOT NULL, -- Add job_description field
    job_category VARCHAR(50) NOT NULL, -- Add job_category field
    location VARCHAR(100), -- Add location field
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (company_id) REFERENCES companies(company_id)
)";
try {
    $pdo->exec($sqlCreateJobApplicationsTable);
    echo "Job applications table created successfully\n";
} catch (PDOException $e) {
    die("Error creating the job applications table: " . $e->getMessage());
}

// Create received_applications table
$sqlCreateReceivedApplicationsTable = "CREATE TABLE IF NOT EXISTS received_applications (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,
    job_application_id INT,
    user_id INT,
    cv_path VARCHAR(255) NOT NULL,
    letter_text TEXT NOT NULL,
    date_applied DATE NOT NULL,
    -- Add other applicant-related fields as needed
    FOREIGN KEY (job_application_id) REFERENCES job_applications(application_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";
try {
    $pdo->exec($sqlCreateReceivedApplicationsTable);
    echo "Received applications table created successfully\n";
} catch (PDOException $e) {
    die("Error creating the received applications table: " . $e->getMessage());
}

// Close the connection
$pdo = null;
?>
