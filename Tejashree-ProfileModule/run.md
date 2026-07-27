# 🚀 How to Run This Project

## What You Need

- **XAMPP** installed (with PHP 8+ and MySQL)
- **Windows** (the commands below are for Windows)

---

## Step 1: Start MySQL

1. Open **XAMPP Control Panel**
2. Click **Start** next to **MySQL**
3. Make sure it shows a green **Running** status

---

## Step 2 (First time only): Set up the Database

Open a command prompt or terminal and run:

```
C:\xampp\mysql\bin\mysql.exe -u root < "C:\path\to\your\project\database\schema.sql"
```

*(Replace the path with the actual location of the `database` folder inside your project)*

---

## Step 3: Start the PHP Server

Open a command prompt / PowerShell in the project folder and run:

```
C:\xampp\php\php.exe -S localhost:8000
```

---

## Step 4: Open in Browser

Go to: **http://localhost:8000**

---

## Demo Logins

| Username       | Password         | Role     |
|----------------|------------------|----------|
| admin          | password123      | Admin    |
| dipalishende   | dipalishende123  | HOD      |
| faculty1       | password123      | Faculty  |
| student1       | password123      | Student  |
| tpo            | password123      | TPO      |

---

## Quick Start (One-Liner)

If you're in the project folder, just run:

```
C:\xampp\php\php.exe -S localhost:8000
```

Then open **http://localhost:8000** in your browser.
