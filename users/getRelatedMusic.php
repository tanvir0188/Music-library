<?php
include '../db.php';

#use jaccard similarity score
function jaccardSimilarity($song1, $song2)
{
    
    $features = ['danceability', 'energy', 'loudness', 'speechiness', 'acousticness', 'instrumentalness', 'liveness', 'valence'];

    
    $song1Features = array_intersect_key($song1, array_flip($features));
    $song2Features = array_intersect_key($song2, array_flip($features));

    
    $intersection = array_intersect_assoc($song1Features, $song2Features);
    $union = array_merge($song1Features, $song2Features);

    
    $similarity = count($intersection) / count($union);

    return $similarity;
}



if (isset($_GET['songId'])) {
    $currentSongId = intval($_GET['songId']);
} else {
    
    exit("Error: Song ID is not provided.");
}

$currentSongQuery = "SELECT * FROM songs WHERE id = $currentSongId";
$currentSongResult = $conn->query($currentSongQuery);

if (!$currentSongResult || $currentSongResult->num_rows == 0) {
    exit("Error: Current song not found.");
}

$currentSong = $currentSongResult->fetch_assoc();


$allSongsQuery = "SELECT * FROM songs";
$allSongsResult = $conn->query($allSongsQuery);
$allSongs = [];

while ($row = $allSongsResult->fetch_assoc()) {
    $allSongs[] = $row;
}


$currentSongIndex = array_search($currentSong, $allSongs);

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

usort($relatedSongs, function ($a, $b) {
    return $b['similarityScore'] <=> $a['similarityScore'];
});

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$totalRelatedSongs = count($relatedSongs);
$relatedSongs = array_slice($relatedSongs, $offset, 5);

$sameArtistSongs = array_filter($allSongs, function ($song) use ($currentSong) {
    return $song['artist'] == $currentSong['artist'];
});

$sameArtistSongCount = count($sameArtistSongs);
?>