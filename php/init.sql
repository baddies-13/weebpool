-- Create database and tables
CREATE DATABASE IF NOT EXISTS resume_db;
USE resume_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    photo VARCHAR(255)
);

-- Experiences table
CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Educations table
CREATE TABLE educations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    degree VARCHAR(255) NOT NULL,
    school_name VARCHAR(255) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Skills table
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    skill_name VARCHAR(255) NOT NULL,
    level VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample user
INSERT INTO users (first_name, last_name, email, phone_number, photo) VALUES
('Paulina', 'Aleba', 'alebapaulina89@gmail.com', '+1234567890', '../images/image.jpg');

-- Insert sample experiences
INSERT INTO experiences (user_id, job_title, company_name, start_date, end_date, description) VALUES
(1, 'Web Developer', 'Tech Company', '2020-01-01', '2022-12-31', 'Developed web applications using HTML, CSS, and JavaScript.');

-- Insert sample educations
INSERT INTO educations (user_id, degree, school_name, start_date, end_date) VALUES
(1, 'Bachelor of Computer Science', 'University of Example', '2016-09-01', '2020-06-30');

-- Insert sample skills
INSERT INTO skills (user_id, skill_name, level) VALUES
(1, 'HTML', 'Expert'),
(1, 'CSS', 'Expert'),
(1, 'JavaScript', 'Intermediate');
