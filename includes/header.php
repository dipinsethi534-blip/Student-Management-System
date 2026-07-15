<!--
    header.php
    -----------
    Common top section for every page: HTML head, Bootstrap CSS,
    our custom CSS, and the navigation bar.
    $page_title should be set by the including page before this file
    is included, so the browser tab shows a meaningful title.
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : "Student Management System"; ?></title>

    <!-- Bootstrap 5 CSS (from CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons (nice touch for buttons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Our own custom styles -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<!-- Top navigation bar, only shown when the admin is logged in -->
<?php if (isset($_SESSION['admin_id'])): ?>
<nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="bi bi-mortarboard-fill"></i> Student Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="view_students.php"><i class="bi bi-people-fill"></i> View Students</a></li>
                <li class="nav-item"><a class="nav-link" href="add_student.php"><i class="bi bi-person-plus-fill"></i> Add Student</a></li>
                <li class="nav-item">
                    <a class="nav-link text-warning" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout (<?php echo htmlspecialchars($_SESSION['admin_username']); ?>)
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container my-4">