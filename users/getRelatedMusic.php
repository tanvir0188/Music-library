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