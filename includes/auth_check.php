<?php
/*
    auth_check.php
    -----------------
    Include this file at the very top of any page that should only
    be accessible AFTER logging in (dashboard, add/edit/delete/view student).

    session_start() must run before any HTML is sent to the browser,
    which is why this file itself has no HTML.
*/

session_start();

// If there is no 'admin_id' in the session, the user is not logged in.
// Redirect them to the login page and stop executing the rest of the script.
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>