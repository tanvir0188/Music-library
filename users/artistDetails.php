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
    $query = "SELECT name, img, preview FROM songs WHERE artist = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $artist);
    $stmt->execute();
    return $stmt->get_result();
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
        tr:nth-child(even) {
            background-color: #121212;
        }

        .container {
            width: auto;
            background-color: #181818;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="#">Your Library</a></li>
            <li><a href="#">More</a></li>
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
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
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