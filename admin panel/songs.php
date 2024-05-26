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

// Sanitize input


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
        #songContainer {
            height: 30%;
            overflow: auto;
        }
        #songList {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: #1e1e1e;
        }
        #songList th,
        #songList td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }
        #songList th {
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
        <a href="index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
        <h2>Song List</h2>
        <input type="text" id="searchBox" placeholder="Search songs">
        <table id="songList">
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

        document.getElementById("searchBox").addEventListener("keyup", function() {
            var input, filter, table, tr, tdName, tdArtist, i, txtValueName, txtValueArtist;
            input = document.getElementById("searchBox");
            filter = input.value.toUpperCase();
            table = document.getElementById("songList");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                tdName = tr[i].getElementsByTagName("td")[1];
                tdArtist = tr[i].getElementsByTagName("td")[2];
                if (tdName && tdArtist) {
                    txtValueName = tdName.textContent || tdName.innerText;
                    txtValueArtist = tdArtist.textContent || tdArtist.innerText;
                    if (txtValueName.toUpperCase().indexOf(filter) > -1 || txtValueArtist.toUpperCase().indexOf(filter) > -1) {
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
