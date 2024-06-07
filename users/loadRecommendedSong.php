<?php
session_start();
require '../db.php';
require './Backend files/fetch_data.php';

$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;

$userLoggedIn = isset($_SESSION['userid']);
$recommendations = [];

if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
    $preferences = fetchUserPreferences($userId);
    $songs = fetchSongs();

    if ($preferences) {
        while ($song = $songs->fetch_assoc()) {
            $song['loudness'] = normalizeLoudness($song['loudness']);
            $song['instrumentalness'] = normalizeInstrumentalness($song['instrumentalness']);
            $similarity = calculateSimilarity($song, $preferences);
            $song['similarity'] = $similarity;
            $recommendations[] = $song;
        }

        usort($recommendations, function ($a, $b) {
            return $a['similarity'] <=> $b['similarity'];
        });

        $recommendations = array_slice($recommendations, $offset, $limit);
    }
}

foreach ($recommendations as $song) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($song['name']) . '</td>';
    echo '<td><img src="' . htmlspecialchars($song['img']) . '" alt="Song Image" width="50"></td>';
    echo '<td><a href="song_preview.php?id=' . $song['id'] . '">Preview</a></td>';
    echo '</tr>';
}
?>
