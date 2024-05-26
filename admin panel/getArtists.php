<?php
include '../db.php';

function fetchArtists($offset) {
    global $conn;
    $artistsQuery = "SELECT DISTINCT artist FROM songs LIMIT 20 OFFSET $offset";
    $artistsResult = $conn->query($artistsQuery);
    return $artistsResult;
}

// Fetch artists based on offset
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    $artistsResult = fetchArtists($offset);

    // Display fetched artists
    while ($row = $artistsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a href='artistSongs.php?artist=" . urlencode($row['artist']) . "'>{$row['artist']}</a></td>";
        echo "</tr>";
    }
}
?>
