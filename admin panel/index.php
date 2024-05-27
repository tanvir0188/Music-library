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
    $query = "SELECT name, artist, img FROM songs ORDER BY popularity DESC LIMIT $limit";
    return $conn->query($query);
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

// Fetch top 10 popular songs
$popularSongs = fetchPopularSongs(10);
// Fetch top 10 popular artists
$popularArtists = fetchPopularArtists(10);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="adminStyle.css">
    <style>
        .list-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .item-card {
            background-color: #1e1e1e;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 0 0 auto;
        }

        .item-card img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }

        .item-card h3 {
            color: #fff;
            font-size: 14px;
            margin: 5px 0;
        }

        .item-card p {
            color: #ccc;
            font-size: 12px;
            margin: 5px 0;
        }

        .see-more {
            text-align: right;
            margin: 10px;
        }

        .see-more a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 14px;
        }

        .see-more a:hover {
            color: #45a049;
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
        <div class="advance-searchbar">
            <form action="searchResults.php" method="GET">
                <input type="text" name="query" placeholder="Search any song or artist" required>
                <input type="submit" value="Search">
            </form>
        </div>

        <h2>Popular Songs</h2>
        <div class="see-more">
            <a href="mostPopularSongs.php">See More</a>
        </div>
        <div class="list-container">
            <?php while ($row = $popularSongs->fetch_assoc()) : ?>
                <div class="item-card">
                    <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Song Image">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['artist']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>

        <h2>Popular Artists</h2>
        <div class="see-more">
            <a href="mostPopularArtists.php">See More</a>
        </div>
        <div class="list-container">
            <?php while ($row = $popularArtists->fetch_assoc()) : ?>
                <div class="item-card">
                    <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Artist Image">
                    <h3><a href="artistSongs.php?artist=<?php echo urlencode($row['artist']); ?>"><?php echo htmlspecialchars($row['artist']); ?></a></h3>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>

</html>
