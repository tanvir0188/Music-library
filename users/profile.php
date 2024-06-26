<?php
session_start();
require '../db.php'; 


if (!isset($_SESSION['userid'])) {
    header('Location: ../loginAnd signup.html');
    exit();
}

$userId = $_SESSION['userid'];
$username = '';
$email = '';
$usertype = '';


$query = "SELECT username, email, usertype FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $email = $user['email'];
    $usertype = $user['usertype'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    
    $updateQuery = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssi', $newUsername, $newEmail, $newPassword, $userId);
    if ($stmt->execute()) {
        $_SESSION['username'] = $newUsername;
        header('Location: profile.php');
        exit();
    } else {
        $error = "Error updating profile.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .main-content {
            padding: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .profile-header img {
            border-radius: 50%;
            margin-right: 20px;
        }

        .profile-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .profile-container form {
            display: flex;
            flex-direction: column;
        }

        .profile-container form input {
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        .profile-container form button {
            padding: 10px;
            background-color: #00848a;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .profile-container form button:hover {
            background-color: #005f5f;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="search_results.php">Search</a></li>
            <li><a href="Details.php">Your Library</a></li>
            <li><a href="profile.php">More</a></li>
            <li><a href="preferenceForm.php">Edit or Add preference</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="profile-header">
            <img src="picture/Artist(1).jpg" alt="Profile Photo" width="100">
            <div>
                <h1><?php echo htmlspecialchars($username); ?></h1>
                <p><?php echo htmlspecialchars($usertype); ?></p>
            </div>
        </div>

        <div class="profile-container">
            <h2>Edit Profile</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form action="profile.php" method="POST">
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <input type="password" name="password" placeholder="New Password" required>
                <button type="submit">Save Changes</button>
            </form>
            <form action="logout.php" method="POST">
                <button type="submit" style="background-color: red;">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>
