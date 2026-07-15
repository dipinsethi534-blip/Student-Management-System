-- =====================================================
-- Student Management System - Database Setup
-- =====================================================
-- How to use:
-- 1. Open phpMyAdmin (or MySQL command line)
-- 2. Run this entire file. It will create the database,
--    the tables, an admin login, and some sample students.
-- =====================================================

-- Create the database (if it doesn't already exist)
CREATE DATABASE IF NOT EXISTS student_management;

-- Select it so all further commands apply to it
USE student_management;

-- -----------------------------------------------------
-- Table: admin
-- Stores the login credentials for the administrator.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- stored as a secure hash, never plain text
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- NOTE: We do NOT insert the admin row here.
-- Passwords must be hashed using PHP's password_hash() function,
-- so the default admin account (username: admin, password: admin123)
-- is created by running database/create_admin.php once in your browser.
-- This guarantees the password hash is 100% compatible with PHP's
-- password_verify() used in login.php.

-- -----------------------------------------------------
-- Table: students
-- Stores all student records.
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_number VARCHAR(20) NOT NULL UNIQUE,
    course VARCHAR(100) NOT NULL,
    semester INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- Sample data so the app has something to show right away
-- -----------------------------------------------------
INSERT INTO students (name, roll_number, course, semester, email, phone, address) VALUES
('Aarav Sharma', 'CSE2201', 'B.Tech CSE', 4, 'aarav.sharma@example.com', '9876543210', 'Ludhiana, Punjab'),
('Priya Verma', 'CSE2202', 'B.Tech CSE', 4, 'priya.verma@example.com', '9876543211', 'Chandigarh'),
('Rohan Gupta', 'CSE2203', 'B.Tech CSE', 4, 'rohan.gupta@example.com', '9876543212', 'Amritsar, Punjab'),
('Sneha Kapoor', 'CSE2204', 'B.Tech CSE', 4, 'sneha.kapoor@example.com', '9876543213', 'Jalandhar, Punjab'),
('Karan Mehta', 'CSE2205', 'B.Tech CSE', 4, 'karan.mehta@example.com', '9876543214', 'Patiala, Punjab');