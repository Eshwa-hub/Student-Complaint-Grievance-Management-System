CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 phone VARCHAR(30) NULL,
 course VARCHAR(120) NULL,
 password VARCHAR(255) NOT NULL,
 role ENUM('student','admin') NOT NULL DEFAULT 'student',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE categories (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE complaints (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 complaint_code VARCHAR(40) NOT NULL UNIQUE,
 student_id INT UNSIGNED NOT NULL,
 category_id INT UNSIGNED NOT NULL,
 subject VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 priority ENUM('Low','Medium','High') NOT NULL DEFAULT 'Medium',
 status ENUM('Pending','In Progress','Resolved','Closed') NOT NULL DEFAULT 'Pending',
 resolution TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_complaint_student FOREIGN KEY(student_id) REFERENCES users(id),
 CONSTRAINT fk_complaint_category FOREIGN KEY(category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE responses (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 complaint_id INT UNSIGNED NOT NULL,
 user_id INT UNSIGNED NOT NULL,
 message TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_response_complaint FOREIGN KEY(complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,
 CONSTRAINT fk_response_user FOREIGN KEY(user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories(name) VALUES
('Academic'),('Examination'),('Library'),('Laboratory'),('Infrastructure'),
('Cleanliness'),('Fees and Accounts'),('Administration'),('Transport'),('Other');

-- Create the first administrator securely after importing the database:
-- 1) Register a normal account through register.php.
-- 2) In phpMyAdmin, run:
-- UPDATE users SET role='admin' WHERE email='your-admin-email@example.com';
