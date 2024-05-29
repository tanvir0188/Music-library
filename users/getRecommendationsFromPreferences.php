<?php
header('Content-Type: application/json');
require '../db.php'; 

$data = json_decode(file_get_contents('php://input'), true);
$user_id = $data['user_id'];

// Fetch the latest preferences of the user
$prefQuery = "SELECT * FROM preferences WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$prefStmt = $conn->prepare($prefQuery);
$prefStmt->bind_param('i', $user_id);
$prefStmt->execute();
$preferences = $prefStmt->get_result()->fetch_assoc();

if (!$preferences) {
    echo json_encode(['error' => 'No preferences found for the user']);
    exit;
}

// Ensure preferences are fetched and assigned correctly
$convertedPreferences = [
    'danceability' => $preferences['danceability'],
    'energy' => $preferences['energy'],
    'loudness' => $preferences['loudness'],
    'speechiness' => $preferences['speechiness'],
    'acousticness' => $preferences['acousticness'],
    'instrumentalness' => $preferences['instrumentalness'],
    'liveness' => $preferences['liveness'],
    'valence' => $preferences['valence']
];

function normalizeLoudness($loudness) {
    // Normalization assuming loudness ranges from -60 dB to 0 dB
    return ($loudness + 60) / 60;
}

function normalizeInstrumentalness($instrumentalness) {
    // Logarithmic scaling for very small values
    return log1p($instrumentalness);
}

// Fetch all songs from the database
$query = "SELECT * FROM songs";
$result = $conn->query($query);

$songs = [];
while ($row = $result->fetch_assoc()) {
    // Normalize loudness and instrumentalness
    $row['loudness'] = normalizeLoudness($row['loudness']);
    $row['instrumentalness'] = normalizeInstrumentalness($row['instrumentalness']);
    $songs[] = $row;
}

function calculateSimilarity($song, $preferences) {
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

$recommendations = [];
foreach ($songs as $song) {
    $similarity = calculateSimilarity($song, $convertedPreferences);
    $song['similarity'] = $similarity;
    $recommendations[] = $song;
}

// Sort recommendations by similarity (lowest distance first)
usort($recommendations, function($a, $b) {
    return $a['similarity'] <=> $b['similarity'];
});

// Limit the number of recommendations (e.g., top 10)
$recommendations = array_slice($recommendations, 0, 10);

echo json_encode($recommendations);
?>
