<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['userid'];
$song_id = $_POST['song_id'];


$checkQuery = "SELECT * FROM favorite_songs WHERE user_id = ? AND song_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $user_id, $song_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    $deleteQuery = "DELETE FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
} else {
    
    $insertQuery = "INSERT INTO favorite_songs (user_id, song_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
}

header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
