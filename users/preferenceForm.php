<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: ../loginAnd signup.html');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Preference Form</title>
</head>
<body>
    <form method="post" action="savePreferences.php">
        <label for="danceability">Danceability:</label>
        <select name="danceability" id="danceability">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="energy">Energy:</label>
        <select name="energy" id="energy">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="loudness">Loudness:</label>
        <select name="loudness" id="loudness">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="speechiness">Speechiness:</label>
        <select name="speechiness" id="speechiness">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="acousticness">Acousticness:</label>
        <select name="acousticness" id="acousticness">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="instrumentalness">Instrumentalness:</label>
        <select name="instrumentalness" id="instrumentalness">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="liveness">Liveness:</label>
        <select name="liveness" id="liveness">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <label for="valence">Valence:</label>
        <select name="valence" id="valence">
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
        </select>
        <br>
        <button type="submit">Save Preferences</button>
    </form>
</body>
</html>
