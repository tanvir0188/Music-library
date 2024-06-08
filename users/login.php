<?php
session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    
    if (empty($username) || empty($password)) {
        echo "Username and Password are required.";
        exit();
    }

    
    $sql = "SELECT id, username, password, usertype FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $usertype);
        $stmt->fetch();

        // Check if the user type is 'normal'
        if ($usertype !== 'normal') {
            echo "Login failed. This user type is not allowed.";
            exit();
        }
        
        if (password_verify($password, $hashed_password)) {
            
            $_SESSION['userid'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['usertype'] = $usertype;

            
            if ($remember) {
                setcookie("userid", $id, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie("username", $username, time() + (86400 * 30), "/");
                setcookie("usertype", $usertype, time() + (86400 * 30), "/");
            }

            echo "Login successful!";
            // Redirect based on usertype
            if ($usertype === 'admin') {
                echo "Login failed. This user type is not allowed.";
            } else {
                header("Location: ../users/index.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
    $stmt->close();
}
$conn->close();
?>
