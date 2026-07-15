<?php
/*
    login.php
    -----------
    Shows the login form and handles the login logic.

    Flow:
    1. If the form was submitted (POST request), validate the input.
    2. Look up the username in the 'admin' table.
    3. Use password_verify() to check the submitted password against
       the hashed password stored in the database.
    4. On success, store admin_id and admin_username in the session
       and redirect to dashboard.php.
    5. On failure, show an error message.
*/

session_start();
require_once 'includes/db_connect.php';

// If the admin is already logged in, send them straight to the dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error_message = "";

// Only run this block when the form has been submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // trim() removes accidental extra spaces the user might type
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        // Prepared statement = safe from SQL injection
        $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            // Compare the typed password with the stored hash
            if (password_verify($password, $admin['password'])) {
                // Correct login -> create session variables
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Invalid username or password.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
        $stmt->close();
    }
}

$page_title = "Login - Student Management System";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="login-wrapper">
    <div class="card login-card">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-mortarboard-fill" style="font-size: 3rem; color: #2c3e50;"></i>
                <h4 class="mt-2 fw-bold">Student Management System</h4>
                <p class="text-muted">Admin Login</p>
            </div>

            <!-- Show an error message if login failed -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <div class="invalid-feedback">Please enter your username.</div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">Please enter your password.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </form>

            <p class="text-center text-muted mt-3 mb-0" style="font-size: 0.85rem;">
                Default: admin / admin123
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>