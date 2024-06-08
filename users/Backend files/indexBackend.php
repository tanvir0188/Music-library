<?php
session_start();
require '../db.php'; 


function fetchPopularSongs($limit)
{
    global $conn;
    $query = "SELECT id, name, img FROM songs ORDER BY popularity DESC LIMIT $limit";
    return $conn->query($query);
}

function fetchPopularArtists($limit)
{
    global $conn;
    $query = "SELECT artist, MAX(img) as img FROM songs GROUP BY artist ORDER BY SUM(popularity) DESC LIMIT $limit";
    return $conn->query($query);
}

function fetchUserPreferences($userId)
{
    global $conn;
    $query = "SELECT * FROM preferences WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchSongs()
{
    global $conn;
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

$userLoggedIn = isset($_SESSION['userid']);
$recommendations = [];
$fullRecommendation = [];

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

        $fullRecommendation=$recommendations;
        $recommendations = array_slice($recommendations, 0, 5);
    }
}



$popularSongs = fetchPopularSongs(5);

$popularArtists = fetchPopularArtists(5);


$userLoggedIn = isset($_SESSION['userid']) && $_SESSION['usertype'] == 'normal';
$preferences = null;

if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
    $preferences = fetchUserPreferences($userId);
}
?>