<?php
include './getRelatedMusic.php';
session_start();

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel="stylesheet" href="css/musicPlayer.css">
    <style>
        /* Add similar CSS styles from previous example */
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
    </style>
</head>

<body>
    <section class="main">
        <div class="sidebar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="search_results.php">Search</a></li>
                <li><a href="#">Your Library</a></li>
                <li><a href="profile.php">More</a></li>
                <li><a href="preferenceForm.php">Edit or Add preference</a></li>
            </ul>
        </div>

        <div class="main-container">
            <div class="music-player">
                <img src="<?php echo htmlspecialchars($currentSong['img']); ?>" alt="Current Song Image"><br>
                <audio controls autoplay>
                    <source src="<?php echo htmlspecialchars($currentSong['preview']); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            <div id="related-song" class="song-table">
                <h3>Related songs</h3>
                <div>
                    <?php if ($offset > 0) : ?>
                        <a href="?songId=<?php echo $currentSongId; ?>&offset=<?php echo $offset - 10; ?>">Previous</a>
                    <?php endif; ?>
                    <?php if ($offset + 10 < $totalRelatedSongs) : ?>
                        <a href="?songId=<?php echo $currentSongId; ?>&offset=<?php echo $offset + 10; ?>">Next</a>
                    <?php endif; ?>
                </div>
                <table>
                    <tbody>
                        <?php foreach ($relatedSongs as $relatedSong) : ?>
                            <?php $isFavorite = isset($_SESSION['userid']) ? isFavorite($_SESSION['userid'], $relatedSong['id'], $conn) : false; ?>
                            <tr>
                                <td style="text-align: start;">
                                    <div class="song-td">
                                        <div class="image">
                                            <img src="<?php echo htmlspecialchars($relatedSong['img']); ?>" alt="<?php echo htmlspecialchars($relatedSong['name']); ?>">
                                        </div>
                                        <div class="content">
                                            <b><a href="?songId=<?php echo $relatedSong['id']; ?>"><?php echo htmlspecialchars($relatedSong['name']); ?></a></b><br>
                                            <a href="artistDetails.php?artist=<?php echo urlencode($relatedSong['artist']); ?>"><?php echo htmlspecialchars($relatedSong['artist']); ?></a>
                                        </div>
                                        <form method="post" action="insertFavorite.php" style="margin-left: 10px;">
                                            <input type="hidden" name="song_id" value="<?php echo $relatedSong['id']; ?>">
                                            <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                                <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z" id="Fill"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td style="text-align: end">
                                    <?php if ($relatedSong['preview']) : ?>
                                        <audio controls >
                                            <source src="<?php echo htmlspecialchars($relatedSong['preview']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    <?php else : ?>
                                        No preview available
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div id="song-from-same-artist" class="song-table">
                <h3>Songs from the same artist</h3>
                <table>
                    <tbody>
                        <?php foreach ($sameArtistSongs as $sameArtistSong) : ?>
                            <?php $isFavorite = isset($_SESSION['userid']) ? isFavorite($_SESSION['userid'], $sameArtistSong['id'], $conn) : false; ?>
                            <tr>
                                <td style="text-align: start;">
                                    <div class="song-td">
                                        <div class="image">
                                            <img src="<?php echo htmlspecialchars($sameArtistSong['img']); ?>" alt="<?php echo htmlspecialchars($sameArtistSong['name']); ?>">
                                        </div>
                                        <div class="content">
                                            <b><a href="?songId=<?php echo $sameArtistSong['id']; ?>"><?php echo htmlspecialchars($sameArtistSong['name']); ?></a></b><br>
                                            <a href="artistDetails.php?artist=<?php echo urlencode($sameArtistSong['artist']); ?>"><?php echo htmlspecialchars($sameArtistSong['artist']); ?></a>
                                        </div>
                                        <form method="post" action="insertFavorite.php" style="margin-left: 10px;">
                                            <input type="hidden" name="song_id" value="<?php echo $sameArtistSong['id']; ?>">
                                            <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                                <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z" id="Fill"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td style="text-align: end">
                                    <?php if ($sameArtistSong['preview']) : ?>
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($sameArtistSong['preview']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    <?php else : ?>
                                        No preview available
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>
