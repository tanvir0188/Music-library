<?php
include '../db.php';

function jaccardSimilarity($song1, $song2)
{
    // Define relevant features for similarity calculation
    $features = ['danceability', 'energy', 'loudness', 'speechiness', 'acousticness', 'instrumentalness', 'liveness', 'valence'];

    // Extract the relevant features from each song
    $song1Features = array_intersect_key($song1, array_flip($features));
    $song2Features = array_intersect_key($song2, array_flip($features));

    // Calculate intersection and union of features between two songs
    $intersection = array_intersect_assoc($song1Features, $song2Features);
    $union = array_merge($song1Features, $song2Features);

    // Calculate Jaccard similarity score
    $similarity = count($intersection) / count($union);

    return $similarity;
}


// Fetch the current song details
if (isset($_GET['songId'])) {
    $currentSongId = intval($_GET['songId']);
} else {
    // Handle case when songId is not provided
    exit("Error: Song ID is not provided.");
}

$currentSongQuery = "SELECT * FROM songs WHERE id = $currentSongId";
$currentSongResult = $conn->query($currentSongQuery);

if (!$currentSongResult || $currentSongResult->num_rows == 0) {
    exit("Error: Current song not found.");
}

$currentSong = $currentSongResult->fetch_assoc();

// Fetch all songs from the database
$allSongsQuery = "SELECT * FROM songs";
$allSongsResult = $conn->query($allSongsQuery);
$allSongs = [];

while ($row = $allSongsResult->fetch_assoc()) {
    $allSongs[] = $row;
}

// Find the index of the current song
$currentSongIndex = array_search($currentSong, $allSongs);

// Calculate similarity scores for all songs and filter out the current song
$relatedSongs = [];
foreach ($allSongs as $index => $song) {
    if ($index != $currentSongIndex) {
        $similarityScore = jaccardSimilarity($currentSong, $song);
        $relatedSongs[] = [
            'id' => $song['id'],
            'name' => $song['name'],
            'artist' => $song['artist'],
            'preview' => $song['preview'],
            'img' => $song['img'],
            'similarityScore' => $similarityScore
        ];
    }
}

// Sort related songs by similarity score in descending order
usort($relatedSongs, function ($a, $b) {
    return $b['similarityScore'] <=> $a['similarityScore'];
});

// Fetch 10 related songs starting from the index after the current song
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$totalRelatedSongs = count($relatedSongs);
$relatedSongs = array_slice($relatedSongs, $offset, 5);

// Fetch details of related songs from the same artist
$sameArtistSongs = array_filter($allSongs, function ($song) use ($currentSong) {
    return $song['artist'] == $currentSong['artist'];
});

$sameArtistSongCount = count($sameArtistSongs);
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
                    <thead>
                    <tr>
                            <th>Song</th>
                            
                            <th>Preview</th>
                        </tr>
                    </thead>
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
                                            <?php echo htmlspecialchars($relatedSong['artist']); ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($relatedSong['preview']) : ?>
                                        <audio controls>
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
                    <thead>
                        <tr>
                            <th>Song</th>
                            
                            <th>Preview</th>
                        </tr>
                    </thead>
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
                                            <?php echo htmlspecialchars($sameArtistSong['artist']); ?>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>
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