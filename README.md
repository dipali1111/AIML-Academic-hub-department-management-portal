<<<<<<< HEAD
# AIML-Academic-hub-department-management-portal
Group Head: Tejashree Desai   Github id's   1)nikhilchandane0221-design 2)chidrewarsanskruti-lab 3)omsai8446 4)tejudesai21 5)mrunmayegalinde 6)premvoid 7)shrutighule105-cyber 8)shreyash-726
=======
<div align="center">
  <br/>
  <img src="assets/images/zeal-logo.png" alt="Zeal Institute" height="60"/>
  <br/><br/>

  # AIML AcademicHub
  ### Department Management Portal

  <p align="center">
    <strong>Zeal Institute of Technology</strong><br/>
    Department of Artificial Intelligence & Machine Learning
  </p>

  <br/>

  <p>
    <img src="https://img.shields.io/badge/PHP-8.2-%23777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP"/>
    <img src="https://img.shields.io/badge/MySQL-8.0-%234479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL"/>
    <img src="https://img.shields.io/badge/HTML5-%23E34F26?style=flat-square&logo=html5&logoColor=white" alt="HTML5"/>
    <img src="https://img.shields.io/badge/CSS3-%231572B6?style=flat-square&logo=css3&logoColor=white" alt="CSS3"/>
    <img src="https://img.shields.io/badge/JavaScript-ES6-%23F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript"/>
    <img src="https://img.shields.io/badge/license-MIT-%23A31F34?style=flat-square" alt="License"/>
  </p>

  <p>
    <img src="https://img.shields.io/badge/role-admin-%23ef4444?style=flat-square" alt="Admin"/>
    <img src="https://img.shields.io/badge/role-HOD-%23f59e0b?style=flat-square" alt="HOD"/>
    <img src="https://img.shields.io/badge/role-faculty-%233b82f6?style=flat-square" alt="Faculty"/>
    <img src="https://img.shields.io/badge/role-student-%2322c55e?style=flat-square" alt="Student"/>
    <img src="https://img.shields.io/badge/role-TPO-%238b5cf6?style=flat-square" alt="TPO"/>
  </p>

  <br/>
</div>

---

## Overview

A unified, paperless digital ecosystem for the **AIML department** — connecting students, faculty, HOD, TPO, and admin on a single platform. Track academics, research, placements, notices, and more with role-based access and real-time insights.

---

## Features

| Module | Description |
|---|---|
| **📊 Dashboard** | Role-based home screen with contextual stats and recent activity |
| **👥 Students** | Manage student records with FY/SY/TY/FINAL YEAR classification |
| **👨‍🏫 Faculty** | Faculty profiles with address, alternate contact, and qualifications |
| **📋 Attendance** | Per-student, per-course attendance tracking |
| **📝 Marks** | Internal, external, and assignment marks with percentage calculation |
| **📢 Notices** | Priority-tagged announcements (normal / important / urgent) |
| **📅 Events** | Department event calendar with venue and date management |
| **💼 Projects** | Student-faculty project assignment with domain and status tracking |
| **🏢 Internships** | Company-wise internship tracking with stipend and duration |
| **🎯 Placements** | Drive management and student offer tracking |
| **🔬 Research** | Funded research projects with funding agency and amount |
| **📄 Publications** | Paper tracking by index type (SCI / Scopus / UGC) |
| **⚖️ Patents** | Patent lifecycle (filed / granted / pending) |
| **📈 Reports** | Analytics: averages, attendance %, and course-wise performance |
| **⚙️ Settings** | Profile management and user administration |

---

## Role-Based Access Control

| Feature | Admin | HOD | Faculty | Student | TPO |
|---|---|---|---|---|---|
| Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Students | ✅ | ✅ | ✅ | ✅ | — |
| Faculty | ✅ | ✅ | ✅ | ✅ | ✅ |
| Attendance | ✅ | ✅ | ✅ | ✅ | ✅ |
| Marks | ✅ | ✅ | ✅ | ✅ | — |
| Notices | ✅ | ✅ | ✅ | ✅ | ✅ |
| Events | ✅ | ✅ | ✅ | ✅ | ✅ |
| Projects | ✅ | ✅ | ✅ | ✅ | ✅ |
| Internships | ✅ | ✅ | ✅ | ✅ | ✅ |
| Placements | ✅ | ✅ | — | ✅ | ✅ |
| Research | ✅ | ✅ | ✅ | — | ✅ |
| Publications | ✅ | ✅ | ✅ | — | ✅ |
| Patents | ✅ | ✅ | ✅ | — | ✅ |
| Reports | ✅ | ✅ | — | — | ✅ |
| Settings | ✅ | ✅ | ✅ | ✅ | ✅ |

> Students see only their own records (attendance, marks, projects, internships).

---

## Tech Stack

```
Frontend         HTML5, CSS3 (custom), JavaScript (vanilla)
Backend          PHP 8.2 (PDO)
Database         MySQL 8.0
Auth             Session-based, bcrypt, CSRF protection
Styling          Custom design system (no Bootstrap/Material)
```

---

## Getting Started

### Prerequisites
- **XAMPP** (PHP 8+ and MySQL)
- A browser

### 1. Clone & Set Up

```bash
git clone https://github.com/dipali1111/AIML-Academic-hub-department-management-portal.git
cd AIML-Academic-hub-department-management-portal
```

### 2. Import Database

Open a terminal and run:

```bash
C:\xampp\mysql\bin\mysql.exe -u root < sql/schema.sql
```

### 3. Start Server

```bash
C:\xampp\php\php.exe -S localhost:8000
```

### 4. Open in Browser

Go to **[http://localhost:8000](http://localhost:8000)**

---

## Demo Credentials

| Username | Password | Role |
|---|---|---|
| `admin` | `password123` | **Admin** |
| `hod` | `password123` | **HOD** |
| `faculty1` | `password123` | **Faculty** |
| `student1` | `password123` | **Student** |
| `tpo` | `password123` | **TPO** |

---

## Project Structure

```
├── assets/
│   ├── css/style.css          # Full responsive stylesheet
│   ├── images/                # Faculty photos, brand assets
│   └── videos/                # Landing page & login backgrounds
├── includes/
│   ├── auth.php               # Session auth, login/logout, role gating
│   ├── db.php                 # PDO connection + base_url() helper
│   ├── functions.php          # CSRF, flash, validation, safe_exec
│   ├── header.php             # Dashboard top bar
│   ├── sidebar.php            # Role-aware sidebar navigation
│   ├── layout.php             # HTML wrapper for dashboard pages
│   └── footer.php             # Closing layout + modal + toast system
├── modules/
│   ├── students.php           # Student CRUD
│   ├── faculty.php            # Faculty CRUD
│   ├── attendance.php         # Attendance tracking
│   ├── marks.php              # Marks entry
│   ├── notices.php            # Notice board
│   ├── events.php             # Event management
│   ├── projects.php           # Project tracking
│   ├── internships.php        # Internship records
│   ├── placements.php         # Placement drives
│   ├── research.php           # Research projects
│   ├── publications.php       # Publication tracking
│   ├── patents.php            # Patent records
│   ├── reports.php            # Analytics dashboard
│   └── settings.php           # Profile & user management
├── sql/
│   ├── schema.sql             # Full database schema (13 tables)
│   └── seed.sql               # Demo data
├── index.php                  # Public landing page
├── login.php                  # Login page
├── logout.php                 # Logout handler
└── dashboard.php              # Post-login home screen
```

---

## Security

- **CSRF tokens** on every form submission
- **Parameterized queries** (PDO prepared statements) — no SQL injection
- **bcrypt password hashing**
- **Session-based auth** with ID regeneration on login
- **Role-gated access** at both sidebar and page level
- **XSS protection** via `htmlspecialchars()` on all output

---

## Team

| Role | Name | GitHub |
|---|---|---|
| **Group Head** | Tejashree Desai | [@tejudesai21](https://github.com/tejudesai21) |
| Member | Nikhil Chandane | [@nikhilchandane0221-design](https://github.com/nikhilchandane0221-design) |
| Member | Sanskruti Chidrewar | [@chidrewarsanskruti-lab](https://github.com/chidrewarsanskruti-lab) |
| Member | Omsai Barhate | [@omsai8446](https://github.com/omsai8446) |
| Member | Mrunmayee | [@mrunmayee-creater](https://github.com/mrunmayee-creater) |
| Member | Prem Gedam | [@premvoid](https://github.com/premvoid) |
| Member | Shruti Ghule | [@shrutighule105-cyber](https://github.com/shrutighule105-cyber) |
| Member | Shreyash | [@shreyash-726](https://github.com/shreyash-726) |

---

<div align="center">
  <br/>
  <sub>Built with ❤️ by the AIML Department, Zeal Institute of Technology</sub>
  <br/>
  <sub>© 2026 AIML AcademicHub</sub>
</div>
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
