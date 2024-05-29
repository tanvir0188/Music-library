<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Preference Form</title>
</head>
<body>
    <h2>Music Preference Form</h2>
    <form action="savePreferences.php" method="post">
        <label for="danceability">Danceability:</label>
        <select name="danceability" id="danceability">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="energy">Energy:</label>
        <select name="energy" id="energy">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="loudness">Loudness:</label>
        <select name="loudness" id="loudness">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="speechiness">Speechiness:</label>
        <select name="speechiness" id="speechiness">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="acousticness">Acousticness:</label>
        <select name="acousticness" id="acousticness">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="instrumentalness">Instrumentalness:</label>
        <select name="instrumentalness" id="instrumentalness">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="liveness">Liveness:</label>
        <select name="liveness" id="liveness">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <label for="valence">Valence:</label>
        <select name="valence" id="valence">
            <option value="very low">Very Low</option>
            <option value="low">Low</option>
            <option value="moderate">Moderate</option>
            <option value="high">High</option>
            <option value="very high">Very High</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
