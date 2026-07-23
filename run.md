# How to Run AIML AcademicHub

## Step 1: Start MySQL

1. Open **XAMPP Control Panel**
2. Click **Start** next to **MySQL** (wait for green status)

---

## Step 2: Create the Database

Open **Command Prompt** and run this (adjust the path to your project folder):

```
C:\xampp\mysql\bin\mysql.exe -u root < "C:\Users\premg\OneDrive\Desktop\aiml_academichub\sql\schema.sql"
```

---

## Step 3: Start the PHP Server

Open **Command Prompt** in your project folder and run:

```
C:\xampp\php\php.exe -S localhost:8000
```

---

## Step 4: Open in Browser

Go to: **http://localhost:8000**

---

## Demo Logins

| Username  | Password      | Role    |
|-----------|---------------|---------|
| admin     | password123   | Admin   |
| hod       | password123   | HOD     |
| faculty1  | password123   | Faculty |
| student1  | password123   | Student |
| tpo       | password123   | TPO     |
