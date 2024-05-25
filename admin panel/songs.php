<?php
include '../db.php';

// Function to fetch songs based on offset
function fetchSongs($offset)
{
    global $conn;
    $songsQuery = "SELECT * FROM songs LIMIT 20 OFFSET $offset";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}

// Function to fetch songs based on search query
function searchSongs($searchQuery)
{
    global $conn;
    $searchQuery = $conn->real_escape_string($searchQuery);
    $songsQuery = "SELECT * FROM songs WHERE name LIKE '%$searchQuery%' OR artist LIKE '%$searchQuery%'";
    $songsResult = $conn->query($songsQuery);
    return $songsResult;
}



// Check if search query is set
if (isset($_GET['search'])) {
    $search = sanitize($_GET['search']);
    $songsResult = searchSongs($search);
} else {
    // Fetch initial songs
    $offset = 0;
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
        #songContainer {
            height: 30%;
            overflow: auto;
        }

        #songList {
            border-collapse: collapse;
            width: 70%;
            margin: auto;
        }

        #songList th,
        #songList td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #songList th {
            background-color: #f2f2f2;
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #searchBox {
            margin: 20px auto;
            display: block;
            width: 50%;
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <h2>Song List</h2>

    <input type="text" id="searchBox" placeholder="Search songs">

    <table id="songList" class="song-container">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Artist</th>
            <th>Image</th>
        </tr>
        <?php
        // Initial offset
        $offset = 0;

        // Fetch initial songs
        $songsResult = fetchSongs($offset);

        // Display initial songs
        while ($row = $songsResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td><a href='artistSongs.php?artist={$row['artist']}'>{$row['artist']}</a></td>";
            echo "<td> <img src='{$row['img']}' height = '100' width = '100'></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <div id="loader"></div>

    <script>
        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                // Show loader
                document.getElementById("loader").style.display = "block";

                // Fetch more songs via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "getSongs.php?offset=" + <?php echo $offset + 20; ?>, true);
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        // Append new songs to the table
                        document.getElementById("songList").insertAdjacentHTML('beforeend', xhr.responseText);
                        // Hide loader
                        document.getElementById("loader").style.display = "none";
                        // Update offset
                        <?php $offset += 20; ?>
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