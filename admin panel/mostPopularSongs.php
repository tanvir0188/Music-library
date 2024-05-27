<?php
session_start();
require '../db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Function to fetch most popular songs
function fetchPopularSongs($limit) {
    global $conn;
    // Assuming there's a 'popularity' column in the 'songs' table to determine popular songs
    $query = "SELECT name, artist, img FROM songs ORDER BY popularity DESC LIMIT $limit";
    return $conn->query($query);
}

// Fetch top 50 popular songs
$popularSongs = fetchPopularSongs(50);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Popular Songs</title>
    <link rel="stylesheet" href="adminStyle.css">
    <style>
        .songlist-container {
            display: flex;
            flex-wrap: wrap;
        }

        .song-card {
            background-color: #1e1e1e;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 1 1 calc(20% - 20px);
        }

        .song-card img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }

        .song-card h3 {
            color: #fff;
            font-size: 14px;
            margin: 5px 0;
        }

        .song-card p {
            color: #ccc;
            font-size: 12px;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="artistList.php">Artist List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
    <h2>Most Popular Songs</h2>
        <div class="songlist-container">
            
            <?php while ($row = $popularSongs->fetch_assoc()) : ?>
                <div class="song-card">
                    <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Song Image">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['artist']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
