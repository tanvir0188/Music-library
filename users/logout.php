<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Delete cookies
if (isset($_COOKIE['userid'])) {
    setcookie('userid', '', time() - 3600, '/');
}
if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
}
if (isset($_COOKIE['usertype'])) {
    setcookie('usertype', '', time() - 3600, '/');
}

// Redirect to login page
header("Location: index.php");
exit();
?>
