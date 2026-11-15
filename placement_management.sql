CREATE DATABASE IF NOT EXISTS placement_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE placement_management;

DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admins;

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  roll_no VARCHAR(40) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  course VARCHAR(60) NOT NULL,
  cgpa DECIMAL(4,2) NOT NULL
);

CREATE TABLE companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_name VARCHAR(140) NOT NULL,
  hr_name VARCHAR(100),
  hr_email VARCHAR(120),
  location VARCHAR(100) NOT NULL,
  website VARCHAR(180)
);

CREATE TABLE jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  job_title VARCHAR(120) NOT NULL,
  skills VARCHAR(255) NOT NULL,
  min_cgpa DECIMAL(4,2) NOT NULL,
  package VARCHAR(40) NOT NULL,
  last_date DATE NOT NULL,
  CONSTRAINT fk_jobs_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

CREATE TABLE applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  job_id INT NOT NULL,
  applied_on DATE NOT NULL,
  status ENUM('Pending','Shortlisted','Selected','Rejected') NOT NULL DEFAULT 'Pending',
  CONSTRAINT fk_app_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  CONSTRAINT fk_app_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  UNIQUE KEY unique_student_job (student_id, job_id)
);

-- Demo admin: username admin, password password
INSERT INTO admins(name, username, password) VALUES
('Placement Administrator','admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Fictional sample records for demonstration only.
INSERT INTO students(name,roll_no,email,phone,course,cgpa) VALUES
('Aditi Sharma','BCA2024-001','aditi.sharma@example.com','9876501001','BCA',8.45),
('Rohan Verma','BCA2024-002','rohan.verma@example.com','9876501002','BCA',7.92),
('Neha Gupta','BCA2024-003','neha.gupta@example.com','9876501003','BCA',8.76),
('Arjun Singh','BCA2024-004','arjun.singh@example.com','9876501004','BCA',7.35),
('Priya Yadav','BCA2024-005','priya.yadav@example.com','9876501005','BCA',9.10),
('Karan Mehta','BCA2024-006','karan.mehta@example.com','9876501006','BCA',6.95);

INSERT INTO companies(company_name,hr_name,hr_email,location,website) VALUES
('TechNova Solutions','Meera Kapoor','careers@technova.example','Noida','https://example.com'),
('BrightByte Technologies','Aman Khanna','jobs@brightbyte.example','Gurugram','https://example.com'),
('NextGen Systems','Ritika Jain','hr@nextgen.example','Agra','https://example.com');

INSERT INTO jobs(company_id,job_title,skills,min_cgpa,package,last_date) VALUES
(1,'Junior Web Developer','HTML, CSS, JavaScript, PHP',6.50,'3.6 LPA','2027-03-31'),
(2,'Data Analyst Trainee','Excel, SQL, Python',7.00,'4.2 LPA','2027-04-15'),
(3,'Software Support Associate','Communication, SQL, MS Office',6.00,'3.2 LPA','2027-04-20'),
(1,'PHP Developer Intern','PHP, MySQL, JavaScript',6.50,'2.4 LPA','2027-05-05');

INSERT INTO applications(student_id,job_id,applied_on,status) VALUES
(1,1,'2026-10-20','Shortlisted'),
(2,2,'2026-10-21','Pending'),
(3,2,'2026-10-22','Selected'),
(4,3,'2026-10-23','Rejected'),
(5,1,'2026-10-24','Pending'),
(6,4,'2026-10-25','Shortlisted');
