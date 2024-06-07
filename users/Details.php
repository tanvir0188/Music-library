<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['userid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['song_id'])) {
    $song_id = $_POST['song_id'];

    $query = "DELETE FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
}

$query = "SELECT songs.id, songs.name AS title, songs.artist, songs.preview
          FROM favorite_songs 
          JOIN songs ON favorite_songs.song_id = songs.id 
          WHERE favorite_songs.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$favoriteSongs = [];

while ($row = $result->fetch_assoc()) {
    $favoriteSongs[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Songs</title>
    <link rel="stylesheet" href="css/details.css">
    <style>
        .artist-header {
            background-color: #282828;

        }

        .artist-header h1 {
            color: #fff;
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

        a {
            text-decoration: none;
            color: #ffffff;
        }

        a:hover {
            color: #00848a;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <img src="picture/Artist(1).jpg" alt="Artist Photo">
            <div>
                <h1>Favorite Songs</h1>
                <p>Description</p>
            </div>
        </div>

        <div class="container">
            <div class="title-bar">
                <h1>Favorite Songs</h1>
                <div class="icons">
                    <i class="fas fa-play"></i>
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-ellipsis-h"></i>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Preview</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($favoriteSongs)) : ?>
                        <?php foreach ($favoriteSongs as $index => $song) : ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $song['id']; ?>">
                                        <?php echo htmlspecialchars($song['title']); ?>
                                    </a>
                                </td>

                                <td><a href="artistDetails.php?artist=<?php echo $song['artist']; ?>"><?php echo htmlspecialchars($song['artist']); ?></a></td>
                                <td>
                                    <?php if (!empty($song['preview'])) : ?>
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($song['preview']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    <?php else : ?>
                                        No preview available
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form method="post" action="">
                                        <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                                        <button type="submit" class="btn btn-remove">
                                            <i class="fas fa-trash-alt"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">No favorite songs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>