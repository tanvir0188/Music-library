<?php
require '../db.php';

$artist = $_GET['artist'];
$artist = $conn->real_escape_string($artist);

$totalSongQuery = "SELECT COUNT(*) as total_songs FROM songs WHERE artist = '$artist'";
$totalSongResult = $conn->query($totalSongQuery);
$totalSongRow = $totalSongResult->fetch_assoc();
$totalSongs = $totalSongRow['total_songs'];

echo $totalSongs;
?>
