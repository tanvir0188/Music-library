<?php
include './getRelatedMusic.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link rel="stylesheet" href="css/musicPlayer.css">
</head>

<body>
    <section class="main">
        <div class="sidebar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#">Your Library</a></li>
                <li><a href="#">More</a></li>
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