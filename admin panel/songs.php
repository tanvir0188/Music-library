<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Function to fetch songs based on offset
function fetchSongs($offset) {
    global $conn;
    $songsQuery = "SELECT * FROM songs LIMIT 20 OFFSET $offset";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}

// Function to fetch songs based on search query
function searchSongs($searchQuery) {
    global $conn;
    $searchQuery = $conn->real_escape_string($searchQuery);
    $songsQuery = "SELECT * FROM songs WHERE name LIKE '%$searchQuery%' OR artist LIKE '%$searchQuery%'";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}



// Initial offset
$offset = 0;

// Check if search query is set
if (isset($_GET['search'])) {
    $search = sanitize($_GET['search']);
    $songsResult = searchSongs($search);
} else {
    // Fetch initial songs
    $songsResult = fetchSongs($offset);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song List</title>
    <link rel="stylesheet" href="adminStyle.css">
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="artistList.php">Artist List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
        <h2>Song List</h2>
        

        <div class="advance-searchbar">
            <form action="songs.php" method="GET">
                <input type="text" id="searchBox" name="search" placeholder="Search any song or artist" required>
                <input type="submit" value="Search">
            </form>
        </div>
        <table id="songList" class="default-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Artist</th>
                <th>Image</th>
            </tr>
            <?php
            // Display initial songs
            while ($row = $songsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td><a href='artistSongs.php?artist=" . urlencode($row['artist']) . "'>{$row['artist']}</a></td>";
                echo "<td><img src='{$row['img']}' height='100' width='100'></td>";
                echo "</tr>";
            }
            ?>
        </table>
        <div id="loader"></div>
    </div>

    <script>
        var offset = <?php echo $offset; ?>;

        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                // Show loader
                document.getElementById("loader").style.display = "block";

                // Fetch more songs via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "getSongs.php?offset=" + (offset + 20), true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        // Append new songs to the table
                        document.getElementById("songList").insertAdjacentHTML('beforeend', xhr.responseText);
                        // Hide loader
                        document.getElementById("loader").style.display = "none";
                        // Update offset
                        offset += 20;
                    }
                };
                xhr.send();
            }
        };
    </script>
</body>
</html>
