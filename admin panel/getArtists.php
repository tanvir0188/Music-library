<?php
include '../db.php';

function fetchArtists($offset) {
    global $conn;
    $artistsQuery = "SELECT artist, COUNT(*) AS song_count FROM songs GROUP BY artist LIMIT 20 OFFSET $offset";
    $artistsResult = $conn->query($artistsQuery);
    return $artistsResult;
}

// Fetch artists based on offset
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    $artistsResult = fetchArtists($offset);

    // Display fetched artists with song counts
    while ($row = $artistsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td><a href='artistSongs.php?artist=" . urlencode($row['artist']) . "'>{$row['artist']}</a></td>";
        echo "<td>{$row['song_count']}</td>"; // Display song count
        echo "</tr>";
    }
}
?>
