<?php
/*
    logout.php
    ------------
    Ends the admin's session and redirects to the login page.
*/

session_start();

// Remove all session variables
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Send the admin back to the login page
header("Location: login.php");
exit();
?>