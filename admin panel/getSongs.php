<?php
include '../db.php';
function fetchSongs($offset) {
    global $conn;
    $songsQuery = "SELECT * FROM songs LIMIT 20 OFFSET $offset";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}

// Fetch songs based on offset
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    $songsResult = fetchSongs($offset);

    // Display fetched songs
    while ($row = $songsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['artist']}</td>";
        echo "<td> <img src='{$row['img']}' height = '100' width = '100'></td>";
        echo "</tr>";
    }
}
?>
