<?php
include '../db.php';

function fetchSongs($offset, $searchQuery = null) {
    global $conn;
    $limit = 20;
    $offset = intval($offset);
    $searchCondition = "";
    
    if ($searchQuery !== null) {
        $searchQuery = $conn->real_escape_string($searchQuery);
        $searchCondition = "WHERE name LIKE '%$searchQuery%' OR artist LIKE '%$searchQuery%'";
    }

    $songsQuery = "SELECT * FROM songs $searchCondition LIMIT $limit OFFSET $offset";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}

// Fetch songs based on offset and search query
if (isset($_GET['offset'])) {
    $offset = intval($_GET['offset']);
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : null;
    $songsResult = fetchSongs($offset, $searchQuery);

    // Display fetched songs
    while ($row = $songsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['artist']}</td>";
        echo "<td> <img src='{$row['img']}' height='100' width='100'></td>";
        echo "</tr>";
    }
}
?>
