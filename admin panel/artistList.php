<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Function to fetch artists based on offset
function fetchArtists($offset) {
    global $conn;
    $artistsQuery = "SELECT DISTINCT artist FROM songs LIMIT 20 OFFSET $offset";
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
    $search = $_GET['search']; // Assume sanitize function is defined elsewhere
    $artistsResult = searchArtists($search);
} else {
    // Fetch initial artists
    $artistsResult = fetchArtists($offset);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist List</title>
    <style>
        /* Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #1e1e1e;
            padding: 10px 0;
            text-align: center;
        }
        nav a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        nav a:hover {
            color: #4CAF50;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .container a {
            color: white;
            text-decoration: none;
        }
        .container a:hover {
            color: #4CAF50;
        }
        #artistContainer {
            height: 30%;
            overflow: auto;
        }
        #artistList {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: #1e1e1e;
        }
        #artistList th,
        #artistList td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }
        #artistList th {
            background-color: #333;
        }
        #loader {
            display: none;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
            position: fixed;
            top: 50%;
            left: 50%;
            margin-top: -60px;
            margin-left: -60px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #searchBox {
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 10px;
            font-size: 16px;
            background-color: #333;
            border: 1px solid #555;
            color: #fff;
        }
        #searchBox::placeholder {
            color: #888;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
        }
        img {
            border-radius: 4px;
        }
    </style>
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
        <table id="artistList">
            <tr>
                <th>Artist</th>
            </tr>
            <?php
            // Display initial artists
            while ($row = $artistsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td><a href='artistSongs.php?artist=" . urlencode($row['artist']) . "'>{$row['artist']}</a></td>";
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
