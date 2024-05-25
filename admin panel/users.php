<?php
// Establish database connection
include 'db.php';

// Fetch users
$usersQuery = "SELECT * FROM users";
$usersResult = $conn->query($usersQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
    <h2>User List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
        <?php
        // Display users
        while ($row = $usersResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['created_at']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
