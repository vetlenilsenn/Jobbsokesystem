**Job Search System Database Overview:**

*Database Name:* job_search_system

*Tables:*

1. **users Table:**
   - Holds user information.
   - Fields:
     - `user_id`: Unique identifier.
     - `username`: User's username.
     - `password`: User's password.
     - `email`: User's email.
     - `name`: Name of user
     - `surname`: Surname of user
     - `is_company`: Indicates if the user is a company.

2. **companies Table:**
   - Stores details of companies.
   - Fields:
     - `company_id`: Unique identifier.
     - `user_id`: Unique identifier linked to the users table.
     - `company_name`: Name of the company.
     - `contact_person`: Contact person within the company.
     - *Foreign Key:* Links to `user_id` in the users table.

3. **job_applications Table:**
   - Records job applications.
   - Fields:
     - `application_id`: Unique identifier.
     - `user_id`: User applying for the job (linked to users table).
     - `company_id`: Company receiving the application (linked to companies table).
     - `job_title`: Title of the job applied for.
     - *Foreign Keys:* Link to `user_id` in users table and `company_id` in companies table.

4. **received_applications Table:**
   - Stores details of received job applications.
   - Fields:
     - `applicant_id`: Unique identifier.
     - `job_application_id`: Linked to the job_applications table.
     - `user_id`: Applicant's user ID (linked to users table).
     - `cv_path`: File path to the applicant's CV.
     - `letter_text`: Text of the application letter.
     - `date_applied`: Date of application.
     - *Foreign Keys:* Link to `job_application_id` in job_applications table and `user_id` in users table.

*Notes:*
- The database is created and connected using PHP Data Objects (PDO).
- Relationships between tables are maintained using foreign keys for data integrity.
- The structure supports tracking users, companies, job applications, and received applications in a comprehensive manner.
