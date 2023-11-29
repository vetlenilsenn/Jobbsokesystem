<?php
// Include your database connection script here using PDO
include('tilkobling.php'); // Adjust the path as needed

try {
    // Generate 10 random users
    for ($i = 1; $i <= 10; $i++) {
        $username = "user_$i";
        $password = password_hash("password_$i", PASSWORD_DEFAULT);
        $email = "user$i@example.com";
        $is_company = 0; // Assuming these are regular users, not companies

        // Insert the generated user data into the users table
        $stmt = $pdo->prepare("
            INSERT INTO users (username, password, email, is_company) 
            VALUES (:username, :password, :email, :is_company)
        ");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':is_company', $is_company, PDO::PARAM_INT);
        $stmt->execute();

        // Get the last inserted user ID
        $user_id = $pdo->lastInsertId();

        // Generate random data for each column in companies
        $company_name = "Company $i";
        $contact_person = "Contact Person $i";

        // Insert the generated data into the companies table
        $stmt = $pdo->prepare("
            INSERT INTO companies (user_id, company_name, contact_person) 
            VALUES (:user_id, :company_name, :contact_person)
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':company_name', $company_name, PDO::PARAM_STR);
        $stmt->bindParam(':contact_person', $contact_person, PDO::PARAM_STR);
        $stmt->execute();

        // Get the last inserted company ID
        $company_id = $pdo->lastInsertId();

        // Generate random data for each column in job_applications
        $job_title = "Job Title $i";

        // Insert the generated data into the job_applications table
        $stmt = $pdo->prepare("
            INSERT INTO job_applications (user_id, company_id, job_title) 
            VALUES (:user_id, :company_id, :job_title)
        ");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':company_id', $company_id, PDO::PARAM_INT);
        $stmt->bindParam(':job_title', $job_title, PDO::PARAM_STR);
        $stmt->execute();
    }

    echo '10 random users, companies, and job applications added successfully.';
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
