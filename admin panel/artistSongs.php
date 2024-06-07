<?php
include '../db.php';

if (isset($_GET['artist'])) {
    $artist = sanitize($_GET['artist']);

    $artistSongsQuery = "SELECT * FROM songs WHERE artist = '$artist'";
    $artistSongsResult = $conn->query($artistSongsQuery);
} else {
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
            width: 100%;
            margin: 20px 0;
            background-color: #1e1e1e;
        }
        #artistSongList th,
        #artistSongList td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }
        #artistSongList th {
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

        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
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
        h2 {
            text-align: center;
            color: #4CAF50;
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
    </div>
    

    <script>
        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                document.getElementById("loader").style.display = "block";

                
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