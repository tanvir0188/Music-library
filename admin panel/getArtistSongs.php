<?php
include '../db.php';



// Check if artist and offset parameters are set
if (isset($_GET['artist']) && isset($_GET['offset'])) {
    $artist = sanitize($_GET['artist']);
    $offset = (int)sanitize($_GET['offset']);

    // Fetch additional songs by the specified artist
    $artistSongsQuery = "SELECT * FROM songs WHERE artist = ? LIMIT 20 OFFSET ?";
    $stmt = $conn->prepare($artistSongsQuery);
    $stmt->bind_param("si", $artist, $offset);
    $stmt->execute();
    $artistSongsResult = $stmt->get_result();

    // Prepare the HTML response
    $response = '';
    while ($row = $artistSongsResult->fetch_assoc()) {
        $response .= "<tr>";
        $response .= "<td>" . htmlspecialchars($row['id']) . "</td>";
        $response .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $response .= "<td><img src='" . htmlspecialchars($row['img']) . "' height='100' width='100'></td>";
        $response .= "</tr>";
    }

    echo $response;
} else {
    // Redirect or handle error if artist parameter or offset is not set
    header("HTTP/1.0 400 Bad Request");
    exit("Error: Required parameters are missing.");
}
?>
