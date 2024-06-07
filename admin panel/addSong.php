<?php
include '../db.php'; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = $_POST['name'];
    $artist = $_POST['artist'];
    $spotify_id = $_POST['spotify_id'];
    $preview = $_POST['preview'] ?? null;
    $image_url = $_POST['image_url'] ?? null;
    $danceability = $_POST['danceability'] ?? null;
    $energy = $_POST['energy'] ?? null;
    $loudness = $_POST['loudness'] ?? null;
    $speechiness = $_POST['speechiness'] ?? null;
    $acousticness = $_POST['acousticness'] ?? null;
    $instrumentalness = $_POST['instrumentalness'] ?? null;
    $liveness = $_POST['liveness'] ?? null;
    $valence = $_POST['valence'] ?? null;
    $acousticness_artist = $_POST['acousticness_artist'] ?? null;
    $danceability_artist = $_POST['danceability_artist'] ?? null;
    $energy_artist = $_POST['energy_artist'] ?? null;
    $instrumentalness_artist = $_POST['instrumentalness_artist'] ?? null;
    $liveness_artist = $_POST['liveness_artist'] ?? null;
    $speechiness_artist = $_POST['speechiness_artist'] ?? null;
    $valence_artist = $_POST['valence_artist'] ?? null;

    
    $sql = "INSERT INTO songs (name, artist, spotify_id, preview, img, danceability, energy, loudness, speechiness, acousticness, instrumentalness, liveness, valence, acousticness_artist, danceability_artist, energy_artist, instrumentalness_artist, liveness_artist, speechiness_artist, valence_artist) 
            VALUES ('$name', '$artist', '$spotify_id', '$preview', '$image_url', $danceability, $energy, $loudness, $speechiness, $acousticness, $instrumentalness, $liveness, $valence, $acousticness_artist, $danceability_artist, $energy_artist, $instrumentalness_artist, $liveness_artist, $speechiness_artist, $valence_artist)";
    
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("New song added successfully!"); window.location = "songs.php";</script>';
    } else {
        echo '<script>alert("Error: ' . $conn->error . '");</script>';
    }
    
    $conn->close();
}
?>
