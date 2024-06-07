<?php
session_start();
require '../db.php'; // Update the path if necessary

$artist = isset($_GET['artist']) ? $_GET['artist'] : '';

if (!$artist) {
    echo "No artist specified.";
    exit();
}

// Function to fetch the artist's image
function fetchArtistImage($artist)
{
    global $conn;
    $query = "SELECT img FROM songs WHERE artist = ? GROUP BY img ORDER BY COUNT(*) DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $artist);
    $stmt->execute();
    $stmt->bind_result($img);
    $stmt->fetch();
    $stmt->close();
    return $img;
}

// Function to fetch songs by artist
function fetchSongsByArtist($artist)
{
    global $conn;
    $query = "SELECT id, name, img, preview FROM songs WHERE artist = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $artist);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to check if a song is a favorite
function isFavorite($user_id, $song_id, $conn)
{
    $query = "SELECT * FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Fetch artist image
$artistImage = fetchArtistImage($artist);

// Fetch songs by the artist
$songs = fetchSongsByArtist($artist);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($artist); ?> - Songs</title>
    <link rel="stylesheet" href="css/details.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 200px;
            background-color: #121212;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #333;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        .artist-header {
            display: flex;
            align-items: center;
        }

        .artist-header img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .container {
            width: auto;
            background-color: #282828;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title-bar h1 {
            margin: 0;
        }

        .title-bar .icons {
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid transparent;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #121212;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 20px;
            border: none;
            background-color: transparent;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn.favorite {
            background-color: #00848a;
        }

        .btn .icon {
            width: 25px;
            height: 25px;
            transition: transform 0.2s ease;
        }

        .btn .icon path {
            fill: white;
            transition: fill 0.2s ease;
        }

        .btn:hover .icon {
            transform: scale(1.2);
        }

        .btn:hover .icon path {
            fill: #00848a;
        }

        .btn.favorite .icon path {
            fill: yellow;
        }

        a {
            text-decoration: none;
            color: #ffffff;
        }

        a:hover {
            color: #00848a;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul>
        <li><a href="index.php">Home</a></li>
            <li><a href="search_results.php">Search</a></li>
            <li><a href="#">Your Library</a></li>
            <li><a href="profile.php">More</a></li>
            <li><a href="preferenceForm.php">Edit or Add preference</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="artist-header">
            <img src="<?php echo htmlspecialchars($artistImage); ?>" alt="<?php echo htmlspecialchars($artist); ?>">
            <div>
                <h1><?php echo htmlspecialchars($artist); ?></h1>
                <p>Popular songs by <?php echo htmlspecialchars($artist); ?></p>
            </div>
        </div>
        <div class="container">
            <div class="title-bar">
                <h1>Songs</h1>
                <div class="icons">
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-share-alt"></i>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Song</th>
                        <th>Image</th>
                        <th>Mp3</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $songs->fetch_assoc()) : ?>
                        <?php $isFavorite = isset($_SESSION['userid']) ? isFavorite($_SESSION['userid'], $row['id'], $conn) : false; ?>
                        <tr id="song_<?php echo $row['id']; ?>">
                            <td style="display: flex; align-items: center;">
                                <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>
                                <form method="post" action="insertFavorite.php" style="margin-left: 10px;">
                                    <input type="hidden" name="song_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                        <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                            <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z" id="Fill"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td><img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="50"></td>
                            <td>
                                <?php if ($row['preview']) : ?>
                                    <audio controls>
                                        <source src="<?php echo htmlspecialchars($row['preview']); ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                <?php else : ?>
                                    No preview available
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>