<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Preference Form</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h2 {
            text-align: center;
            background-color: #4CAF50;
            padding: 10px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        label:nth-child(odd) {
            color: #1abc9c;
        }

        label:nth-child(even) {
            color: #9b59b6;
        }

        select {
            width: calc(100% - 16px);
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
            background-color: #444;
            color: white;
        }

        select option {
            background-color: #333;
            color: white;
        }

        input[type="submit"],
        .button a {
            display: inline-block;
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button a {
            background-color: red;
        }

        input[type="submit"]:hover,
        .button a:hover {
            background-color: #45a049;
        }

        .button {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <h2>Music Preference Form</h2>
    <form action="savePreferences.php" method="post">
        <label for="danceability">Danceability:</label>
        <select name="danceability" id="danceability">
            <option value="very low" <?php if (isset($user_preferences['danceability']) && $user_preferences['danceability'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['danceability']) && $user_preferences['danceability'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['danceability']) && $user_preferences['danceability'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['danceability']) && $user_preferences['danceability'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['danceability']) && $user_preferences['danceability'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="energy">Energy:</label>
        <select name="energy" id="energy">
            <option value="very low" <?php if (isset($user_preferences['energy']) && $user_preferences['energy'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['energy']) && $user_preferences['energy'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['energy']) && $user_preferences['energy'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['energy']) && $user_preferences['energy'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['energy']) && $user_preferences['energy'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>


        <label for="loudness">Loudness:</label>
        <select name="loudness" id="loudness">
            <option value="very low" <?php if (isset($user_preferences['loudness']) && $user_preferences['loudness'] == -40) echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['loudness']) && $user_preferences['loudness'] == -20) echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['loudness']) && $user_preferences['loudness'] == -12.5) echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['loudness']) && $user_preferences['loudness'] == -5) echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['loudness']) && $user_preferences['loudness'] == 0) echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="speechiness">Speechiness:</label>
        <select name="speechiness" id="speechiness">
            <option value="very low" <?php if (isset($user_preferences['speechiness']) && $user_preferences['speechiness'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['speechiness']) && $user_preferences['speechiness'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['speechiness']) && $user_preferences['speechiness'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['speechiness']) && $user_preferences['speechiness'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['speechiness']) && $user_preferences['speechiness'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="acousticness">Acousticness:</label>
        <select name="acousticness" id="acousticness">
            <option value="very low" <?php if (isset($user_preferences['acousticness']) && $user_preferences['acousticness'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['acousticness']) && $user_preferences['acousticness'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['acousticness']) && $user_preferences['acousticness'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['acousticness']) && $user_preferences['acousticness'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['acousticness']) && $user_preferences['acousticness'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="instrumentalness">Instrumentalness:</label>
        <select name="instrumentalness" id="instrumentalness">
            <option value="very low" <?php if (isset($user_preferences['instrumentalness']) && $user_preferences['instrumentalness'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['instrumentalness']) && $user_preferences['instrumentalness'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['instrumentalness']) && $user_preferences['instrumentalness'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['instrumentalness']) && $user_preferences['instrumentalness'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['instrumentalness']) && $user_preferences['instrumentalness'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="liveness">Liveness:</label>
        <select name="liveness" id="liveness">
            <option value="very low" <?php if (isset($user_preferences['liveness']) && $user_preferences['liveness'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['liveness']) && $user_preferences['liveness'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['liveness']) && $user_preferences['liveness'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['liveness']) && $user_preferences['liveness'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['liveness']) && $user_preferences['liveness'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>

        <label for="valence">Valence:</label>
        <select name="valence" id="valence">
            <option value="very low" <?php if (isset($user_preferences['valence']) && $user_preferences['valence'] == 'very low') echo 'selected="selected"'; ?>>Very Low</option>
            <option value="low" <?php if (isset($user_preferences['valence']) && $user_preferences['valence'] == 'low') echo 'selected="selected"'; ?>>Low</option>
            <option value="moderate" <?php if (isset($user_preferences['valence']) && $user_preferences['valence'] == 'moderate') echo 'selected="selected"'; ?>>Moderate</option>
            <option value="high" <?php if (isset($user_preferences['valence']) && $user_preferences['valence'] == 'high') echo 'selected="selected"'; ?>>High</option>
            <option value="very high" <?php if (isset($user_preferences['valence']) && $user_preferences['valence'] == 'very high') echo 'selected="selected"'; ?>>Very High</option>
        </select><br><br>


        <div class="button">
            <input type="submit" value="Submit">
            <a href="index.php">Go back</a>
        </div>

    </form>
</body>

</html>