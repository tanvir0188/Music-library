<?php
session_start();
require '../db.php'; 


function fetchPopularSongs($offset, $limit, $conn)
{
    $query = "SELECT id, name, img, preview FROM songs ORDER BY popularity DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    return $stmt->get_result();
}


function isFavorite($user_id, $song_id, $conn)
{
    $query = "SELECT * FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Songs</title>
    <link rel="stylesheet" href="css/details.css">
    <style>
    
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 200px;
            background-color: #121212;
            position: fixed;
            height: 100%;
            padding-top: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
            text-align: center;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #333;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        .container {
            width: auto;
            background-color: #282828;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title-bar h1 {
            margin: 0;
        }

        .title-bar .icons {
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid transparent;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #121212;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 20px;
            border: none;
            background-color: transparent;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn.favorite {
            background-color: #00848a;
        }

        .btn .icon {
            width: 25px;
            height: 25px;
            transition: transform 0.2s ease;
        }

        .btn .icon path {
            fill: white;
            transition: fill 0.2s ease;
        }

        .btn:hover .icon {
            transform: scale(1.2);
        }

        .btn:hover .icon path {
            fill: #00848a;
        }

        .btn.favorite .icon path {
            fill: yellow;
        }

        a {
            text-decoration: none;
            color: #ffffff;
        }

        a:hover {
            color: #00848a;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="search_results.php">Search</a></li>
            <li><a href="Details.php">Your Library</a></li>
            <li><a href="profile.php">More</a></li>
            <li><a href="preferenceForm.php">Edit or Add preference</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container">
            <div class="title-bar">
                <h1>Popular Songs</h1>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Song</th>
                        <th>Image</th>
                        <th>Preview</th>
                    </tr>
                </thead>
                <tbody id="song-table-body">
                </tbody>
            </table>
        </div>
    </div>

    <script>
        let offset = 0;
        const limit = 50;
        const songTableBody = document.getElementById('song-table-body');

        // Function to load more songs
        function loadSongs() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `loadPopularSong.php?offset=${offset}&limit=${limit}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const response = this.responseText;
                    songTableBody.innerHTML += response;
                    offset += limit;
                }
            };
            xhr.send();
        }

        // Infinite scrolling
        window.onscroll = function () {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
                loadSongs();
            }
        };

        // Load the initial songs
        loadSongs();
    </script>
</body>

</html>
