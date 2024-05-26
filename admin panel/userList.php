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
    <link rel="stylesheet" href="adminStyle.css">
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
        <table id="userList" class="default-table">
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