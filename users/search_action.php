<?php
require '../db.php'; 

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $searchQuery = "%$searchQuery%";

    $stmt = $conn->prepare("SELECT * FROM songs WHERE name LIKE ? OR artist LIKE ?");
    $stmt->bind_param('ss', $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    $songs = [];
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }

    session_start();
    $_SESSION['search_results'] = $songs;
    header('Location: search_results.php');
    exit();
}
?>
