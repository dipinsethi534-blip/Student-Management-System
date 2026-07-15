<?php
/*
    delete_student.php
    ---------------------
    Deletes a student record based on ?id=... in the URL.
    The JavaScript confirm() dialog (see js/script.js) already asked
    the user "Are you sure?" before this page is ever requested,
    but we still validate the ID here on the server side too.
*/

require_once 'includes/auth_check.php';
require_once 'includes/db_connect.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_students.php?error=Invalid student ID.");
    exit();
}

$id = intval($_GET['id']);

// Prepared statement, even for delete, to stay safe from SQL injection
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: view_students.php?success=Student deleted successfully!");
} else {
    header("Location: view_students.php?error=Could not delete student. Please try again.");
}

$stmt->close();
exit();
?>