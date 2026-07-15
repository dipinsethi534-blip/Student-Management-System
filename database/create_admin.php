<?php
/*
    create_admin.php
    -----------------
    Run this file ONCE in your browser (e.g. http://localhost/student_management/database/create_admin.php)
    AFTER you have imported student_management.sql.

    It creates the default admin account:
        Username: admin
        Password: admin123

    We generate the password hash using PHP's own password_hash()
    function so it will always work correctly with password_verify()
    in login.php.

    IMPORTANT: Delete this file (or rename it) after you have run it once,
    so nobody else can use it to reset the admin password.
*/

require_once '../includes/db_connect.php';

$username = 'admin';
$plain_password = 'admin123';

// password_hash() automatically creates a secure, salted hash.
// We never store the plain password in the database.
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// Check if the admin already exists, to avoid creating duplicates
$check = $conn->prepare("SELECT id FROM admin WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    // Admin already exists -> update the password instead
    $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $username);
    $stmt->execute();
    echo "Admin account already existed. Password has been reset to 'admin123'.";
} else {
    // Admin does not exist -> insert a new row
    $stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    echo "Admin account created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br><br>";
    echo "<strong>Please delete this file (create_admin.php) now for security.</strong>";
}

$stmt->close();
$conn->close();
?>