<?php
session_start();
require '../db.php'; // Update the path if necessary

// Check if the user is logged in
$userLoggedIn = isset($_SESSION['userid']);
$fullRecommendation = [];

if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
    $preferences = fetchUserPreferences($userId, $conn);
    $songs = fetchSongs($conn);

    if ($preferences) {
        while ($song = $songs->fetch_assoc()) {
            $song['loudness'] = normalizeLoudness($song['loudness']);
            $song['instrumentalness'] = normalizeInstrumentalness($song['instrumentalness']);
            $similarity = calculateSimilarity($song, $preferences);
            $song['similarity'] = $similarity;
            $fullRecommendation[] = $song;
        }

        usort($fullRecommendation, function ($a, $b) {
            return $a['similarity'] <=> $b['similarity'];
        });

        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $limit = 50;
        $totalSongs = count($fullRecommendation);
        $recommendations = array_slice($fullRecommendation, $offset, $limit);
    }
}

function fetchUserPreferences($userId, $conn)
{
    $query = "SELECT * FROM preferences WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchSongs($conn)
{
    $query = "SELECT * FROM songs";
    return $conn->query($query);
}

function normalizeLoudness($loudness)
{
    return ($loudness + 60) / 60;
}

function normalizeInstrumentalness($instrumentalness)
{
    return log1p($instrumentalness);
}

function calculateSimilarity($song, $preferences)
{
    return sqrt(
        pow($song['danceability'] - $preferences['danceability'], 2) +
            pow($song['energy'] - $preferences['energy'], 2) +
            pow($song['loudness'] - normalizeLoudness($preferences['loudness']), 2) +
            pow($song['speechiness'] - $preferences['speechiness'], 2) +
            pow($song['acousticness'] - $preferences['acousticness'], 2) +
            pow($song['instrumentalness'] - normalizeInstrumentalness($preferences['instrumentalness']), 2) +
            pow($song['liveness'] - $preferences['liveness'], 2) +
            pow($song['valence'] - $preferences['valence'], 2)
    );
}

function isFavorite($user_id, $song_id, $conn)
{
    $query = "SELECT * FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommended Songs</title>
    <link rel="stylesheet" href="css/details.css">
    <style>
        /* Existing styles */
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
            <li><a href="Details.php">Your Library</a></li>
            <li><a href="profile.php">More</a></li>
            <li><a href="preferenceForm.php">Edit or Add preference</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="title-bar">
                <h1>Recommended Songs</h1>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Song</th>

                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody id="song-table-body">
                    <?php foreach ($recommendations as $song) : ?>
                        <?php $isFavorite = isFavorite($userId, $song['id'], $conn); ?>
                        <tr id="song_<?php echo $song['id']; ?>">
                            <td style="display: flex; align-items: center;">
                                <img style="margin-right: 5px;" src="<?php echo htmlspecialchars($song['img']); ?>" alt="Song Image" width="50">
                                <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $song['id']; ?>"><?php echo htmlspecialchars($song['name']); ?></a>
                                <form method="post" action="insertFavorite.php">
                                    <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                                    <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                        <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                            <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.207,2.286a3.542,3.542,0,0,0,0,4.97L8.752,13,15.3,7.256a3.542,3.542,0,0,0,0-4.97,3.545,3.545,0,0,0-4.974,0l-1.39,1.4L6.109,2.286A3.442,3.442,0,0,0,4.72,1.25Z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>

                            <td><audio controls>
                                    <source src="<?php echo htmlspecialchars($song['preview']); ?>" type="audio/mpeg">Your browser does not support the audio element.
                                </audio></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($offset + $limit < $totalSongs) : ?>
                <div class="load-more">
                    <a href="recommendedSongList.php?offset=<?php echo $offset + $limit; ?>">Load More</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>