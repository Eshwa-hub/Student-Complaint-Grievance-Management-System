
# Student Complaint & Grievance Management System

A web-based Student Complaint and Grievance Management System developed for R.C. Patel Arts, Commerce, Science College, Shirpur.

## About the Project

The Student Complaint & Grievance Management System provides an online platform where students can register, submit complaints, and track their complaint progress. Administrators can review complaints, update their status, and provide responses.

## College Details

**College:** R.C. Patel Arts, Commerce, Science College, Shirpur

**Educational Trust:** R.C. Patel Educational Trust

## Main Features

- Student registration and login
- Secure user authentication
- Online complaint submission
- Complaint category and priority selection
- Complaint status tracking
- Student dashboard
- Admin dashboard
- Complaint status management
- Admin responses and remarks
- College-branded user interface

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- XAMPP
- InfinityFree

## Project Structure

```text
Student-Complaint-Grievance-System/
├── assets/
│   ├── style.css
│   └── rcpet-logo.png
├── partials/
│   ├── header.php
│   └── footer.php
├── admin.php
├── complaint.php
├── config.example.php
├── dashboard.php
├── database.sql
├── index.php
├── login.php
├── logout.php
├── new_complaint.php
└── register.php
```

## Installation Using XAMPP

1. Install XAMPP.
2. Copy the project folder into `C:\xampp\htdocs`.
3. Start Apache and MySQL.
4. Open `http://localhost/phpmyadmin`.
5. Create a database named `student_grievance`.
6. Import the `database.sql` file.
7. Copy `config.example.php` to `config.php`.
8. Enter your local MySQL credentials in `config.php`.
9. Open `http://localhost/student_grievance_system/`.

## Admin Access

Register an account first. The administrator role must be assigned manually by an authorized database administrator.

## Hosting

The project can be hosted on a PHP and MySQL-supported hosting service such as InfinityFree.

Configure the hosting database credentials separately in `config.php`.

## Developer

Student Project – BCA

R.C. Patel Arts, Commerce, Science College, Shirpur
