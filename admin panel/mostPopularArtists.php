<?php
session_start();
require '../db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Function to fetch most popular artists by aggregating song popularity
function fetchPopularArtists($limit) {
    global $conn;
    $query = "SELECT artist, SUM(popularity) as total_popularity, MAX(img) as img
              FROM songs
              GROUP BY artist
              ORDER BY total_popularity DESC
              LIMIT $limit";
    return $conn->query($query);
}

// Fetch top 50 popular artists
$popularArtists = fetchPopularArtists(50);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Popular Artists</title>
    <link rel="stylesheet" href="adminStyle.css">
    <style>
        .artistlist-container {
            display: flex;
            flex-wrap: wrap;
        }

        .artist-card {
            background-color: #1e1e1e;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 1 1 calc(20% - 20px);
        }

        .artist-card img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }

        .artist-card h3 {
            color: #fff;
            font-size: 14px;
            margin: 5px 0;
        }

        .artist-card p {
            color: #ccc;
            font-size: 12px;
            margin: 5px 0;
        }

        .container {
            padding: 20px;
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
        <h2>Most Popular Artists</h2>
        <div class="artistlist-container">
            <?php while ($row = $popularArtists->fetch_assoc()) : ?>
                <div class="artist-card">
                    <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Artist Image">
                    <h3><?php echo htmlspecialchars($row['artist']); ?></h3>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
