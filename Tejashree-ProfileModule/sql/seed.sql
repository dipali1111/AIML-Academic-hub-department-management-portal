-- AIML AcademicHub Seed Data
USE aiml_academichub;

-- Password for all users: 'password123' (bcrypt hash)
SET @pw = '$2y$10$1aAEaGvmi9OFOPk2gRlFTOAry9mehLRz2QWInvG74j9dSy27IBRY.';

-- Users
INSERT INTO users (username, password, role, full_name, email, phone) VALUES
('admin', @pw, 'admin', 'Dr. Admin Kumar', 'admin@aiml.edu', '9999000001'),
('hod', @pw, 'hod', 'Dr. HOD Sharma', 'hod@aiml.edu', '9999000002'),
('faculty1', @pw, 'faculty', 'Prof. Anita Desai', 'anita@aiml.edu', '9999000003'),
('faculty2', @pw, 'faculty', 'Prof. Rahul Mehta', 'rahul@aiml.edu', '9999000004'),
('student1', @pw, 'student', 'Rohan Patil', 'rohan@aiml.edu', '9999000005'),
('student2', @pw, 'student', 'Sneha Joshi', 'sneha@aiml.edu', '9999000006'),
('tpo', @pw, 'tpo', 'Mr. TPO Nair', 'tpo@aiml.edu', '9999000007');

-- Faculty
INSERT INTO faculty (user_id, employee_id, full_name, email, phone, designation, qualification, joined_date) VALUES
(3, 'F001', 'Prof. Anita Desai', 'anita@aiml.edu', '9999000003', 'Assistant Professor', 'M.Tech AIML', '2021-07-15'),
(4, 'F002', 'Prof. Rahul Mehta', 'rahul@aiml.edu', '9999000004', 'Associate Professor', 'Ph.D CSE', '2019-06-10');

-- Students
INSERT INTO students (user_id, roll_no, full_name, email, phone, year, semester, division, cgpa, address) VALUES
(5, 'AIML21-001', 'Rohan Patil', 'rohan@aiml.edu', '9999000005', 3, 5, 'A', 8.75, 'Pune, Maharashtra'),
(6, 'AIML21-002', 'Sneha Joshi', 'sneha@aiml.edu', '9999000006', 3, 5, 'A', 9.10, 'Mumbai, Maharashtra');

-- Courses
INSERT INTO courses (course_code, course_name, semester, credits, faculty_id) VALUES
('AIML301', 'Machine Learning', 5, 4, 1),
('AIML302', 'Deep Learning', 5, 4, 2),
('AIML303', 'Computer Vision', 5, 3, 1);

-- Attendance
INSERT INTO attendance (student_id, course_id, att_date, status) VALUES
(1, 1, '2026-07-01', 'present'),
(1, 1, '2026-07-02', 'present'),
(1, 1, '2026-07-03', 'absent'),
(2, 1, '2026-07-01', 'present'),
(2, 1, '2026-07-02', 'late'),
(2, 1, '2026-07-03', 'present');

-- Marks
INSERT INTO marks (student_id, course_id, exam_type, marks_obtained, max_marks) VALUES
(1, 1, 'internal', 42, 50),
(1, 1, 'external', 78, 100),
(2, 1, 'internal', 47, 50),
(2, 1, 'external', 88, 100);

-- Notices
INSERT INTO notices (title, content, posted_by, priority) VALUES
('Semester End Examinations', 'End semester exams will begin from 1st August 2026.', 2, 'important'),
('Workshop on Generative AI', 'A two-day workshop on GenAI is scheduled this Friday.', 3, 'normal'),
('Placement Drive Next Week', 'TCS hiring drive for final year students.', 7, 'urgent');

-- Events
INSERT INTO events (title, description, event_date, venue, created_by) VALUES
('AI Summit 2026', 'Annual department AI summit with industry speakers.', '2026-08-15', 'Seminar Hall', 2),
('Hackathon', '24-hour national level hackathon.', '2026-09-10', 'Lab 3', 3);

-- Projects
INSERT INTO projects (title, description, student_id, faculty_id, status, domain) VALUES
('Crop Disease Detection', 'CNN based detection of crop diseases.', 1, 1, 'ongoing', 'Computer Vision'),
('Chatbot for College', 'NLP based query resolving chatbot.', 2, 2, 'completed', 'NLP');

-- Internships
INSERT INTO internships (student_id, company_name, role, start_date, end_date, stipend, status) VALUES
(1, 'Infosys', 'AI Intern', '2026-05-01', '2026-07-01', 15000, 'completed'),
(2, 'Persistent', 'ML Intern', '2026-06-01', '2026-08-01', 20000, 'ongoing');

-- Companies
INSERT INTO companies (name, contact_person, email, phone, domain) VALUES
('TCS', 'Mr. Verma', 'campus@tcs.com', '9123456780', 'IT Services'),
('Infosys', 'Ms. Rao', 'hr@infosys.com', '9123456781', 'IT Services'),
('Persistent', 'Mr. Kulkarni', 'careers@persistent.com', '9123456782', 'Software');

-- Placements
INSERT INTO placements (company_id, drive_title, drive_date, position, package, student_id, offer_status) VALUES
(1, 'TCS Ninja', '2026-07-25', 'Systems Engineer', 350000, 1, 'registered'),
(2, 'Infosys Specialist', '2026-07-20', 'Data Analyst', 400000, 2, 'selected');

-- Research
INSERT INTO research (title, area, faculty_id, funding_agency, amount, status, year) VALUES
('Explainable AI in Healthcare', 'XAI', 1, 'AICTE', 500000, 'ongoing', 2026),
('Federated Learning for IoT', 'FL', 2, 'DST', 750000, 'proposed', 2026);

-- Publications
INSERT INTO publications (title, authors, journal, publication_year, index_type, faculty_id) VALUES
('Survey on Transformers', 'Anita Desai, Rahul Mehta', 'IEEE Access', 2025, 'SCI', 1),
('Optimizing CNNs', 'Rahul Mehta', 'Springer', 2024, 'Scopus', 2);

-- Patents
INSERT INTO patents (title, inventors, application_no, status, filed_date, faculty_id) VALUES
('Smart Attendance System', 'Anita Desai', 'APP2026001', 'granted', '2025-03-12', 1),
('Energy Efficient Model', 'Rahul Mehta', 'APP2026002', 'filed', '2026-01-20', 2);
