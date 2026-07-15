<?php
/*
    index.php
    -----------
    This is the default page opened when someone visits the project
    (e.g. http://localhost/student_management/). It doesn't show any
    content itself — it just redirects to the correct page.
*/

session_start();

if (isset($_SESSION['admin_id'])) {
    // Already logged in -> go straight to the dashboard
    header("Location: dashboard.php");
} else {
    // Not logged in -> go to the login page
    header("Location: login.php");
}
exit();
?>