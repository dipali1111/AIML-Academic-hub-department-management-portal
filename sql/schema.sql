-- AIML AcademicHub Database Schema
-- MySQL / PHP (PDO)
CREATE DATABASE IF NOT EXISTS aiml_academichub CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE aiml_academichub;

-- -----------------------------
-- Users (auth + roles)
-- -----------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','hod','faculty','student','tpo') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    profile_pic VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------
-- Students
-- -----------------------------
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    roll_no VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    year INT,
    semester INT,
    division VARCHAR(10),
    cgpa DECIMAL(4,2) DEFAULT 0.00,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------
-- Faculty
-- -----------------------------
CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    employee_id VARCHAR(30) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
<<<<<<< HEAD
=======
    alt_phone VARCHAR(20) DEFAULT NULL,
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
    designation VARCHAR(50),
    department VARCHAR(50) DEFAULT 'AIML',
    qualification VARCHAR(100),
    joined_date DATE,
<<<<<<< HEAD
=======
    address TEXT DEFAULT NULL,
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------
-- Courses
-- -----------------------------
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) NOT NULL UNIQUE,
    course_name VARCHAR(100) NOT NULL,
    semester INT,
    credits INT DEFAULT 3,
    faculty_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- -----------------------------
-- Attendance
-- -----------------------------
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    att_date DATE NOT NULL,
    status ENUM('present','absent','late') DEFAULT 'present',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (student_id, course_id, att_date),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- -----------------------------
-- Marks
-- -----------------------------
CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    exam_type VARCHAR(30) DEFAULT 'internal',
    marks_obtained DECIMAL(6,2) DEFAULT 0,
    max_marks DECIMAL(6,2) DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- -----------------------------
-- Notices
-- -----------------------------
CREATE TABLE IF NOT EXISTS notices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    content TEXT,
    posted_by INT,
    priority ENUM('normal','important','urgent') DEFAULT 'normal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------
-- Events
-- -----------------------------
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE,
    venue VARCHAR(100),
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- -----------------------------
-- Projects
-- -----------------------------
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    student_id INT,
    faculty_id INT,
    status ENUM('proposed','ongoing','completed') DEFAULT 'proposed',
    domain VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- -----------------------------
-- Internships
-- -----------------------------
CREATE TABLE IF NOT EXISTS internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    company_name VARCHAR(100),
    role VARCHAR(100),
    start_date DATE,
    end_date DATE,
    stipend DECIMAL(10,2) DEFAULT 0,
    status ENUM('applied','ongoing','completed','rejected') DEFAULT 'applied',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
);

-- -----------------------------
-- Companies (for TPO)
-- -----------------------------
CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    domain VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------
-- Placements (drive + student offers)
-- -----------------------------
CREATE TABLE IF NOT EXISTS placements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    drive_title VARCHAR(150),
    drive_date DATE,
    position VARCHAR(100),
    package DECIMAL(10,2) DEFAULT 0,
    student_id INT,
    offer_status ENUM('registered','selected','rejected','pending') DEFAULT 'registered',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE SET NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
);

-- -----------------------------
-- Research
-- -----------------------------
CREATE TABLE IF NOT EXISTS research (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    area VARCHAR(100),
    faculty_id INT,
    funding_agency VARCHAR(100),
    amount DECIMAL(12,2) DEFAULT 0,
    status ENUM('proposed','ongoing','completed') DEFAULT 'proposed',
    year YEAR,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- -----------------------------
-- Publications
-- -----------------------------
CREATE TABLE IF NOT EXISTS publications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    authors VARCHAR(200),
    journal VARCHAR(150),
    publication_year YEAR,
    index_type VARCHAR(30),
    faculty_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);

-- -----------------------------
-- Patents
-- -----------------------------
CREATE TABLE IF NOT EXISTS patents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    inventors VARCHAR(200),
    application_no VARCHAR(50),
    status ENUM('filed','granted','pending') DEFAULT 'filed',
    filed_date DATE,
    faculty_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id) ON DELETE SET NULL
);
