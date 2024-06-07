<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php'); 
    exit();
}

require '../db.php'; 

$user_id = $_SESSION['userid'];


$query = "SELECT * FROM preferences WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_preferences = array();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_preferences['danceability'] = $row['danceability'];
    $user_preferences['energy'] = $row['energy'];
    $user_preferences['loudness'] = $row['loudness'];
    $user_preferences['speechiness'] = $row['speechiness'];
    $user_preferences['acousticness'] = $row['acousticness'];
    $user_preferences['instrumentalness'] = $row['instrumentalness'];
    $user_preferences['liveness'] = $row['liveness'];
    $user_preferences['valence'] = $row['valence'];
}


function convertPreference($preference, $type)
{
    switch ($type) {
        case 'loudness':
            
            switch ($preference) {
                case 'very low':
                    return -40; 
                case 'low':
                    return -20;
                case 'moderate':
                    return -12.5;
                case 'high':
                    return -5;
                case 'very high':
                    return 0; 
            }
            break;
        case 'instrumentalness':
            
            switch ($preference) {
                case 'very low':
                    return 0; 
                case 'low':
                    return 0.05;
                case 'moderate':
                    return 0.3;
                case 'high':
                    return 0.75;
                case 'very high':
                    return 1.0; 
            }
            break;
        default:
            
            switch ($preference) {
                case 'very low':
                    return 0; 
                case 'low':
                    return 0.1;
                case 'moderate':
                    return 0.5;
                case 'high':
                    return 0.9;
                case 'very high':
                    return 1.0; 
            }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $danceability = convertPreference($_POST['danceability'], 'danceability');
    $energy = convertPreference($_POST['energy'], 'energy');
    $loudness = convertPreference($_POST['loudness'], 'loudness');
    $speechiness = convertPreference($_POST['speechiness'], 'speechiness');
    $acousticness = convertPreference($_POST['acousticness'], 'acousticness');
    $instrumentalness = convertPreference($_POST['instrumentalness'], 'instrumentalness');
    $liveness = convertPreference($_POST['liveness'], 'liveness');
    $valence = convertPreference($_POST['valence'], 'valence');


    
    $query = "SELECT * FROM preferences WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $query = "UPDATE preferences SET danceability = ?, energy = ?, loudness = ?, speechiness = ?, acousticness = ?, instrumentalness = ?, liveness = ?, valence = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ddddddddi', $danceability, $energy, $loudness, $speechiness, $acousticness, $instrumentalness, $liveness, $valence, $user_id);
    } else {
        $query = "INSERT INTO preferences (user_id, danceability, energy, loudness, speechiness, acousticness, instrumentalness, liveness, valence) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('idddddddd', $user_id, $danceability, $energy, $loudness, $speechiness, $acousticness, $instrumentalness, $liveness, $valence);
    }
    

    if ($stmt->execute()) {
        header('Location: index.php'); 
    } else {
        echo "Error: " . $stmt->error;
    }
    exit();
}
?>
