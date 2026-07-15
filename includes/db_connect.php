<?php
/*
    db_connect.php
    ----------------
    This file creates a single MySQL connection ($conn) that every
    other page includes. If you move this project to a different
    computer/server, you only need to change the settings below.
*/

$db_host = "localhost";     // usually "localhost" on XAMPP/WAMP
$db_user = "root";          // default XAMPP/WAMP MySQL username
$db_pass = "";              // default XAMPP/WAMP MySQL password (empty)
$db_name = "student_management";

// Create connection using MySQLi (object-oriented style)
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// If the connection fails, stop the script and show an error
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Make sure data is stored/read using UTF-8 (supports names, symbols, etc.)
$conn->set_charset("utf8mb4");
?>