<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "music_library";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function sanitize($input) {
    global $conn;
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $conn->real_escape_string($input);
}
?>
