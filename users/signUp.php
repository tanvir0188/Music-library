<?php
session_start();

require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword= $_POST['confirm-password'];
    $email = $_POST['email'];
    $usertype = 'normal';


    if (empty($username) || empty($password) || empty($email) || empty($confirmPassword)) {
        echo "All fields are required.";
        exit();
    }
    if ($password != $confirmPassword) {
        echo "Password didn't match";
        exit();
    }


    $sql = "INSERT INTO users (username, usertype, password, email) VALUES (?, ?, ?, ?)";


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $usertype, $hashedPassword, $email);

    if ($stmt->execute()) {
    
        echo "Registration successful!";
    
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $stmt->error;
    }


    $stmt->close();
}
?>
