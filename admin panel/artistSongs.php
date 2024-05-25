<?php
include '../db.php';

// Check if artist parameter is set
if (isset($_GET['artist'])) {
    $artist = sanitize($_GET['artist']);
    
    // Fetch songs by the specified artist
    $artistSongsQuery = "SELECT * FROM songs WHERE artist = '$artist'";
    $artistSongsResult = $conn->query($artistSongsQuery);
} else {
    // Redirect if artist parameter is not set
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Songs by <?php echo $artist; ?></title>
    <style>
        #songContainer {
            height: 30%;
            overflow: auto;
        }

        #artistSongList {
            border-collapse: collapse;
            width: 70%;
            margin: auto;
        }

        #artistSongList th,
        #artistSongList td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #artistSongList th {
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

<h2>Songs by <?php echo $artist; ?></h2>

<input type="text" id="searchBox" placeholder="Search songs">

<table id="artistSongList" class="song-container">
    <tr>
        <th>Serial no</th>
        <th>Name</th>
        <th>Image</th>
    </tr>
    <?php
    $counter = 1;
    while ($row = $artistSongsResult->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$counter}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td> <img src='{$row['img']}' height='100' width='100'></td>";
        echo "</tr>";
        $counter++;
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
            xhr.open("GET", "getArtistSongs.php?artist=<?php echo $artist; ?>&offset=" + document.getElementById("artistSongList").rows.length, true);
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Append new songs to the table
                    document.getElementById("artistSongList").insertAdjacentHTML('beforeend', xhr.responseText);
                    // Hide loader
                    document.getElementById("loader").style.display = "none";
                }
            };
            xhr.send();
        }
    };

    document.getElementById("searchBox").addEventListener("keyup", function() {
        var input, filter, table, tr, tdName, i, txtValueName;
        input = document.getElementById("searchBox");
        filter = input.value.toUpperCase();
        table = document.getElementById("artistSongList");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            tdName = tr[i].getElementsByTagName("td")[1]; 
            if (tdName) {
                txtValueName = tdName.textContent || tdName.innerText;
                if (txtValueName.toUpperCase().indexOf(filter) > -1) {
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
