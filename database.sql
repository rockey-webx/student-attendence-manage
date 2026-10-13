CREATE DATABASE IF NOT EXISTS attendance_management;

USE attendance_management;

-- Students
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roll_no INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    prn VARCHAR(100),
    class_name VARCHAR(50),
    division VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Teachers
CREATE TABLE teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    teacher_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects
CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
    ON DELETE SET NULL
);

-- Lectures
CREATE TABLE lectures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecture_date DATE NOT NULL,
    lecture_number INT NOT NULL,
    lecture_type ENUM('Practical', 'Theory') NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    start_time TIME,
    end_time TIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (subject_id) REFERENCES subjects(id)
    ON DELETE CASCADE,

    FOREIGN KEY (teacher_id) REFERENCES teachers(id)
    ON DELETE CASCADE
);

-- Attendance
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lecture_id INT NOT NULL,
    student_id INT NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,

    UNIQUE KEY unique_attendance (lecture_id, student_id),

    FOREIGN KEY (lecture_id) REFERENCES lectures(id)
    ON DELETE CASCADE,

    FOREIGN KEY (student_id) REFERENCES students(id)
    ON DELETE CASCADE
);