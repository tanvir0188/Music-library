<?php
session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $usertype = $_POST['usertype'];
    
    if (empty($username) || empty($password) || empty($email) || empty($usertype)) {
        echo "All fields are required.";
        exit();
    }

    $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Username or email already exists.";
    } else {
        $sql = "INSERT INTO users (username, usertype, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $usertype, $password, $email);
        
        if ($stmt->execute()) {
            echo "User registered successfully.";
            header("Location: songs.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
}
$conn->close();
?>
