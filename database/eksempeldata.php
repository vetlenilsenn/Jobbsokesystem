<?php
// Include your database connection file (adjust the path accordingly)
require_once 'tilkobling.php';

// Example data for users table
$usersData = [
    ['username' => 'Isak', 'password' => password_hash('Isak', PASSWORD_DEFAULT), 'email' => 'john@example.com', 'name' => 'John', 'surname' => 'Doe', 'is_company' => 0, 'profile_picture' => 'path/to/profile_picture.jpg', 'searchable' => true, 'user_category' => 'Marketing'],
    ['username' => 'Vetle', 'password' => password_hash('Vetle', PASSWORD_DEFAULT), 'email' => 'info@companyxyz.com', 'name' => 'Company', 'surname' => 'XYZ', 'is_company' => 1, 'profile_picture' => 'path/to/company_logo.jpg', 'searchable' => false, 'user_category' => NULL]
];

// Example data for companies table
$companiesData = [
    ['user_id' => 2, 'company_name' => 'XYZ Ltd', 'contact_person' => 'Bob Johnson']
];

// Example data for job_applications table
$jobApplicationsData = [
    ['user_id' => 2, 'company_id' => 1, 'job_title' => 'Software Developer', 'company_name' => 'ABC Corp', 'job_description' => 'Developing software applications', 'job_category' => 'IT', 'location' => 'City A'],
    ['user_id' => 2, 'company_id' => 1, 'job_title' => 'Marketing Specialist', 'company_name' => 'XYZ Ltd', 'job_description' => 'Marketing and promotion', 'job_category' => 'Marketing', 'location' => 'City B']
];

// Example data for received_applications table
$receivedApplicationsData = [
    ['job_application_id' => 1, 'user_id' => 2, 'cv_path' => 'path/to/cv_alice.pdf', 'letter_text' => 'I am interested in the software developer position.', 'date_applied' => '2023-01-15'],
    ['job_application_id' => 2, 'user_id' => 2, 'cv_path' => 'path/to/cv_bob.pdf', 'letter_text' => 'I have experience in marketing and promotion.', 'date_applied' => '2023-01-16']
];

// Insert example data into users table
foreach ($usersData as $userData) {
    $insertQuery = "INSERT INTO users (username, password, email, name, surname, is_company, profile_picture, searchable, user_category) VALUES (:username, :password, :email, :name, :surname, :is_company, :profile_picture, :searchable, :user_category)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute($userData);
}

// Insert example data into companies table
foreach ($companiesData as $companyData) {
    $insertQuery = "INSERT INTO companies (user_id, company_name, contact_person) VALUES (:user_id, :company_name, :contact_person)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute($companyData);
}

// Insert example data into job_applications table
foreach ($jobApplicationsData as $jobApplicationData) {
    $insertQuery = "INSERT INTO job_applications (user_id, company_id, job_title, company_name, job_description, job_category, location) VALUES (:user_id, :company_id, :job_title, :company_name, :job_description, :job_category, :location)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute($jobApplicationData);
}

// Insert example data into received_applications table
foreach ($receivedApplicationsData as $receivedApplicationData) {
    $insertQuery = "INSERT INTO received_applications (job_application_id, user_id, cv_path, letter_text, date_applied) VALUES (:job_application_id, :user_id, :cv_path, :letter_text, :date_applied)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->execute($receivedApplicationData);
}

echo "Example data added successfully.";
?>
