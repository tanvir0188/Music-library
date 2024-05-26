<?php
session_start();
require '../db.php';

if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Song</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .add-song {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 5px;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-group div {
            flex: 1 1 calc(50% - 10px);
        }

        .form-group div.full-width {
            flex: 1 1 100%;
        }

        label {
            font-size: 14px;
        }

        input[type=text],
        input[type=number] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #333;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #333;
            color: #ffffff;
        }

        input[type=submit],
        .btn-back {
            width: 48%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        input[type=submit]:hover,
        .btn-back:hover {
            background-color: #45a049;
        }

        .btn-back {
            background-color: #f44336;
        }

        .btn-back:hover {
            background-color: #e53935;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
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

        <div class="add-song">
            <form action="addSong.php" method="POST">
                <div class="form-group">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div>
                        <label for="artist">Artist:</label>
                        <input type="text" id="artist" name="artist" required>
                    </div>

                    <div>
                        <label for="spotify_id">Spotify ID:</label>
                        <input type="text" id="spotify_id" name="spotify_id" required>
                    </div>

                    <div>
                        <label for="preview">Preview URL:</label>
                        <input type="text" id="preview" name="preview">
                    </div>

                    <div>
                        <label for="image_url">Image URL:</label>
                        <input type="text" id="image_url" name="image_url">
                    </div>

                    <div>
                        <label for="danceability">Danceability:</label>
                        <input type="number" id="danceability" name="danceability" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="energy">Energy:</label>
                        <input type="number" id="energy" name="energy" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="loudness">Loudness:</label>
                        <input type="number" id="loudness" name="loudness" step="0.001">
                    </div>

                    <div>
                        <label for="speechiness">Speechiness:</label>
                        <input type="number" id="speechiness" name="speechiness" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="acousticness">Acousticness:</label>
                        <input type="number" id="acousticness" name="acousticness" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="instrumentalness">Instrumentalness:</label>
                        <input type="number" id="instrumentalness" name="instrumentalness" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="liveness">Liveness:</label>
                        <input type="number" id="liveness" name="liveness" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="valence">Valence:</label>
                        <input type="number" id="valence" name="valence" min="0" max="1" step="0.001">
                    </div>

                    <div>
                        <label for="acousticness_artist">Acousticness (Artist):</label>
                        <input type="number" id="acousticness_artist" name="acousticness_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="danceability_artist">Danceability (Artist):</label>
                        <input type="number" id="danceability_artist" name="danceability_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="energy_artist">Energy (Artist):</label>
                        <input type="number" id="energy_artist" name="energy_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="instrumentalness_artist">Instrumentalness (Artist):</label>
                        <input type="number" id="instrumentalness_artist" name="instrumentalness_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="liveness_artist">Liveness (Artist):</label>
                        <input type="number" id="liveness_artist" name="liveness_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="speechiness_artist">Speechiness (Artist):</label>
                        <input type="number" id="speechiness_artist" name="speechiness_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div>
                        <label for="valence_artist">Valence (Artist):</label>
                        <input type="number" id="valence_artist" name="valence_artist" min="0" max="1" step="0.000000001">
                    </div>

                    <div class="full-width button-group">
                        <input type="submit" value="Add Song">
                        <a href="songs.php" class="btn-back">Back to Songs</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>