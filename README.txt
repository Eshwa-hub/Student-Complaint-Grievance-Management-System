STUDENT COMPLAINT & GRIEVANCE MANAGEMENT SYSTEM
================================================

REQUIREMENTS
- PHP 7.4+ (PHP 8.x recommended)
- MySQL 5.7+ / MariaDB
- Apache web server (XAMPP or PHP hosting)

LOCAL INSTALLATION (XAMPP)
1. Copy the extracted folder into C:\xampp\htdocs\student_grievance_system
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin
4. Create a database named student_grievance.
5. Select the database and import database.sql.
6. Open config.php and set:
   DB_HOST = localhost
   DB_NAME = student_grievance
   DB_USER = root
   DB_PASS = (blank by default in a standard XAMPP install)
7. Open http://localhost/student_grievance_system/

CREATE ADMIN ACCOUNT
1. Register through the website using the email you want for the admin.
2. In phpMyAdmin, select your database and run:
   UPDATE users SET role='admin' WHERE email='your-email@example.com';
3. Log out and sign in again. The admin dashboard will open.

INFINITYFREE / SHARED HOSTING
1. Create a MySQL database in the hosting control panel.
2. Import database.sql through phpMyAdmin.
3. Edit config.php with the exact database hostname, database name,
   username and password provided by the host. Do not assume localhost.
4. Upload the CONTENTS of this folder into htdocs (not an extra nested folder).
5. Ensure index.php is directly inside htdocs.
6. Register your account, then promote it to admin using the SQL above.
7. Test student registration, complaint submission and admin status updates.

IMPORTANT SECURITY NOTES
- Change database credentials before deployment.
- Use HTTPS.
- Never share the admin password or hosting credentials.
- The first admin is promoted manually in phpMyAdmin.
- This is an academic starter application. Before real institutional use,
  add institutional approval, privacy/retention policies, audit logging,
  backups, rate limiting, CSRF review, security testing and access controls
  appropriate to sensitive complaints.
- This starter stores no uploaded attachments, to avoid unsafe file upload
  handling in the initial version.

FILES
- index.php: public landing page
- register.php / login.php: account access
- dashboard.php: student dashboard
- new_complaint.php: complaint submission
- complaint.php: complaint details and updates
- admin.php: admin dashboard and case management
- database.sql: database schema and categories
- config.php: database connection and common helpers
