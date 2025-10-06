-- SQL Commands to Create Database and Table
-- Copy and paste these commands in your MySQL/phpMyAdmin

-- 1. Create Database (if not already created)
CREATE DATABASE IF NOT EXISTS if0_39995906_class;

-- 2. Use the database
USE if0_39995906_class;

-- 3. Create Student Table
CREATE TABLE student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_number VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    date_of_birth DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Check if table is created (Optional)
DESCRIBE student;

-- 5. Sample data insert (Optional)
-- INSERT INTO student (roll_number, name, age, date_of_birth) 
-- VALUES ('CS001', 'John Doe', 20, '2003-05-15');