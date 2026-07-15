#Group Project for Semester 4
# Student Management System

A simple, beginner-friendly **Student Management System** built with
HTML, CSS, JavaScript, PHP (MySQLi, procedural-style OOP), MySQL, and
Bootstrap 5. Built as a BTech CSE Semester 4 college project.

---

## Features

- Admin login (with hashed passwords using `password_hash()` / `password_verify()`)
- Dashboard with quick statistics
- Add student
- Edit student
- Delete student (with confirmation popup)
- Search student (by name, roll number, or course)
- View all students in a responsive table
- Stores: Name, Roll Number, Course, Semester, Email, Phone, Address
- Server-side AND client-side validation
- Success/error messages
- Logout
- Fully responsive UI (Bootstrap 5)

---

## Folder Structure

```
student_management/
├── css/
│   └── style.css              # Custom styles on top of Bootstrap
├── js/
│   └── script.js               # Form validation + delete confirmation
├── images/                     # For logos/photos (empty by default)
├── database/
│   ├── student_management.sql  # Creates DB + tables + sample students
│   └── create_admin.php        # One-time script to create the admin login
├── includes/
│   ├── db_connect.php          # MySQL connection (edit your DB settings here)
│   ├── auth_check.php          # Blocks access if not logged in
│   ├── header.php              # Shared navbar + page head
│   └── footer.php              # Shared footer + script tags
├── index.php                   # Redirects to login or dashboard
├── login.php                   # Admin login page
├── logout.php                  # Destroys session
├── dashboard.php                # Stats overview after login
├── add_student.php             # Add a new student
├── edit_student.php            # Edit an existing student
├── delete_student.php          # Delete a student
├── view_students.php           # View + search all students
└── README.md
```

---

## Requirements

- [XAMPP](https://www.apachefriends.org/) / WAMP / MAMP (Apache + PHP + MySQL)
- Any modern browser
- (Internet connection needed only to load Bootstrap from CDN — or you can
  download Bootstrap locally if your viva/demo has no internet access)

---

## Setup Instructions (Step by Step)

1. **Install XAMPP** and start the **Apache** and **MySQL** services from the XAMPP Control Panel.

2. **Copy the project folder**
   Copy the entire `student_management` folder into your XAMPP `htdocs` directory, e.g.:
   ```
   C:\xampp\htdocs\student_management
   ```

3. **Create the database**
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Click **Import** → choose the file `database/student_management.sql`
   - Click **Go**. This creates the `student_management` database along with
     the `admin` and `students` tables, plus 5 sample students.

4. **Create the admin login**
   - In your browser, visit:
     ```
     http://localhost/student_management/database/create_admin.php
     ```
   - This creates the default admin account:
     - **Username:** `admin`
     - **Password:** `admin123`
   - After it runs successfully, it's good practice to delete or rename
     `create_admin.php` so nobody else can use it.

5. **Check your database settings**
   Open `includes/db_connect.php` and confirm these match your MySQL setup
   (the defaults below work for a fresh XAMPP install):
   ```php
   $db_host = "localhost";
   $db_user = "root";
   $db_pass = "";
   $db_name = "student_management";
   ```

6. **Run the project**
   Open your browser and visit:
   ```
   http://localhost/student_management/
   ```
   You'll be redirected to the login page. Log in with `admin` / `admin123`.

---

## How Each Part Works (Viva Prep Notes)

- **Login (`login.php`)** — Takes username/password, looks up the admin row
  by username using a prepared statement, then uses `password_verify()` to
  compare the typed password with the bcrypt hash stored in the database.
  On success it sets `$_SESSION['admin_id']`.

- **Session protection (`includes/auth_check.php`)** — Every protected page
  includes this file first. If `$_SESSION['admin_id']` isn't set, the user
  is redirected to `login.php`. This is what stops someone from directly
  opening `dashboard.php` without logging in.

- **Prepared statements** — All SQL queries that use user input (`add_student.php`,
  `edit_student.php`, `delete_student.php`, `view_students.php`) use
  `$conn->prepare()` with `?` placeholders and `bind_param()`. This prevents
  **SQL Injection**, a key security concept examiners often ask about.

- **`htmlspecialchars()`** — Used whenever we print student data back onto
  the page. This prevents **Cross-Site Scripting (XSS)** by converting
  special characters like `<` and `>` into safe HTML entities.

- **Validation happens twice:**
  1. **Client-side** (`js/script.js` + Bootstrap's `needs-validation` class) —
     gives instant feedback without reloading the page.
  2. **Server-side** (PHP in `add_student.php` / `edit_student.php`) — the
     real security layer, since JavaScript can be disabled by the user.

- **CRUD operations map to files:**
  - **C**reate → `add_student.php`
  - **R**ead → `view_students.php`, `dashboard.php`
  - **U**pdate → `edit_student.php`
  - **D**elete → `delete_student.php`

- **Search (`view_students.php`)** — Uses SQL's `LIKE` operator with `%`
  wildcards to match the search term against name, roll number, or course.

---

## Sample Login

| Username | Password  |
|----------|-----------|
| admin    | admin123  |

## Sample Students (already inserted by the SQL file)

| Name          | Roll No.  | Course     | Semester |
|---------------|-----------|------------|----------|
| Aarav Sharma  | CSE2201   | B.Tech CSE | 4        |
| Priya Verma   | CSE2202   | B.Tech CSE | 4        |
| Rohan Gupta   | CSE2203   | B.Tech CSE | 4        |
| Sneha Kapoor  | CSE2204   | B.Tech CSE | 4        |
| Karan Mehta   | CSE2205   | B.Tech CSE | 4        |

---

## Possible Future Improvements (good talking points for viva)

- Add pagination on `view_students.php` for large numbers of students
- Add "forgot password" functionality for the admin
- Export student list to CSV/PDF
- Add profile photo upload for students (using the `images/` folder)
- Add multiple admin roles (super admin vs staff)

---

## Notes

- Bootstrap 5 and Bootstrap Icons are loaded from a CDN. If you'll be
  demoing without internet, download the Bootstrap CSS/JS files and
  place them in the `css/` and `js/` folders, then update the `<link>`
  and `<script>` paths in `includes/header.php`, `includes/footer.php`,
  and `login.php`.
- Passwords are never stored in plain text — only bcrypt hashes.
