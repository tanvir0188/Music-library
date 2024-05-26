<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Function to fetch artists based on offset
function fetchArtistsWithSongCount($offset) {
    global $conn;
    $artistsQuery = "SELECT artist, COUNT(*) AS song_count FROM songs GROUP BY artist LIMIT 20 OFFSET $offset";
    $artistResult = $conn->query($artistsQuery);
    return $artistResult;
}

// Function to fetch artists based on search query
function searchArtists($searchQuery) {
    global $conn;
    $searchQuery = $conn->real_escape_string($searchQuery);
    $artistsQuery = "SELECT DISTINCT artist FROM songs WHERE artist LIKE '%$searchQuery%'";
    $artistsResult = $conn->query($artistsQuery);
    return $artistsResult;
}

// Initial offset
$offset = 0;

// Check if search query is set
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $artistsResult = searchArtists($search);
} else {
    // Fetch initial artists
    $artistsResult = fetchArtistsWithSongCount($offset);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist List</title>
    <link rel="stylesheet" href="adminStyle.css">
</head>
<body>
    <nav>
        <a href="../index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="artistList.php">Artist List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
        <h2>Artist List</h2>
        <input type="text" id="searchBox" placeholder="Search artists">
        <table id="artistList" class="default-table">
            <tr>
                <th>Artist</th>
                <th>Total Songs</th> <!-- New column header -->
            </tr>
            <?php
            // Display initial artists with song count
            while ($row = $artistsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td><a href='artistSongs.php?artist=" . urlencode($row['artist']) . "'>{$row['artist']}</a></td>";
                echo "<td>{$row['song_count']}</td>"; // Display song count
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

                // Fetch more artists via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "getArtists.php?offset=" + (offset + 20), true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        // Append new artists to the table
                        document.getElementById("artistList").insertAdjacentHTML('beforeend', xhr.responseText);
                        // Hide loader
                        document.getElementById("loader").style.display = "none";
                        // Update offset
                        offset += 20;
                    }
                };
                xhr.send();
            }
        };

        document.getElementById("searchBox").addEventListener("keyup", function() {
            var input, filter, table, tr, tdArtist, i, txtValueArtist;
            input = document.getElementById("searchBox");
            filter = input.value.toUpperCase();
            table = document.getElementById("artistList");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                tdArtist = tr[i].getElementsByTagName("td")[0];
                if (tdArtist) {
                    txtValueArtist = tdArtist.textContent || tdArtist.innerText;
                    if (txtValueArtist.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
</body>
</html>
