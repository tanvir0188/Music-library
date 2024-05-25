<?php
include '../db.php';

// Check if artist parameter is set
if (isset($_GET['artist']) && isset($_GET['offset'])) {
    $artist = sanitize($_GET['artist']);
    $offset = (int)$_GET['offset'];

    // Fetch additional songs by the specified artist
    $artistSongsQuery = "SELECT * FROM songs WHERE artist = '$artist' LIMIT 20 OFFSET $offset";
    $artistSongsResult = $conn->query($artistSongsQuery);

    // Prepare the HTML response
    $response = '';
    while ($row = $artistSongsResult->fetch_assoc()) {
        $response .= "<tr>";
        $response .= "<td>{$row['id']}</td>";
        $response .= "<td>{$row['name']}</td>";
        $response .= "<td> <img src='{$row['img']}' height='100' width='100'></td>";
        $response .= "</tr>";
    }

    echo $response;
} else {
    // Redirect or handle error if artist parameter or offset is not set
    header("HTTP/1.0 400 Bad Request");
    exit("Error: Required parameters are missing.");
}
?>
