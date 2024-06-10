<?php
session_start();
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        echo "Username and Password are required.";
        exit();
    }

    // Prepare and execute the SQL statement
    $sql = "SELECT id, username, password, usertype FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if a user was found
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $usertype);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Check if the user is an admin
            if ($usertype === 'admin') {
                // Set session variables
                $_SESSION['userid'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['usertype'] = $usertype;

                // Set cookies if 'remember me' is checked
                if ($remember) {
                    setcookie("userid", $id, time() + (86400 * 30), "/"); // 86400 = 1 day
                    setcookie("username", $username, time() + (86400 * 30), "/");
                    setcookie("usertype", $usertype, time() + (86400 * 30), "/");
                }

                echo "Login successful!";
                // Redirect to the admin panel
                header("Location: ../admin panel/index.php");
                exit();
            } else {
                // User is not an admin
                echo "Access denied. Only admins can log in.";
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
