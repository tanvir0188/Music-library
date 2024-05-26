<?php
session_start();
include '../db.php';


// Check if the user is logged in and if the user is an admin
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all users from the database
$userListQuery = "SELECT * FROM users";
$userListResult = $conn->query($userListQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
        /* Add your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .container a {
            color: white;
            text-decoration: none;
        }

        .container a:hover {
            color: #4CAF50;
        }

        #userList {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: #1e1e1e;
        }

        #userList th,
        #userList td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }

        #userList th {
            background-color: #333;
        }

        nav {
            background-color: #1e1e1e;
            padding: 10px 0;
            text-align: center;
        }

        nav a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }

        nav a:hover {
            color: #4CAF50;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }
    </style>
</head>

<body>
    <nav>
        <a href="../index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="artistList.php">Artist List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
        <h2>User List</h2>
        <table id="userList">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Usertype</th>
                <th>Created At</th>
            </tr>
            <?php
            while ($row = $userListResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['username']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['usertype']}</td>";
                echo "<td>{$row['created_at']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>