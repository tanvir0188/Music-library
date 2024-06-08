<?php
require '../db.php'; 

// Define the query to get distinct artists and their most frequent image
$sql = "SELECT artist, 
               (SELECT img 
                FROM songs 
                WHERE artist = s.artist 
                GROUP BY img 
                ORDER BY COUNT(*) DESC 
                LIMIT 1) as img
        FROM songs s
        GROUP BY artist
        ORDER BY SUM(popularity) DESC";

$result = $conn->query($sql);

$artists = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $artists[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Artists</title>
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
        .artists {
            display: flex;
            flex-wrap: wrap;
        }
        .artist {
            width: 150px;
            margin: 10px;
            text-align: center;
        }
        .artist img {
            width: 100%;
            border-radius: 50%;
        }
        .artist a {
            text-decoration: none;
            color: #fff;
        }
        .artist a:hover {
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
                <h1>Popular Artists</h1>
            </div>
            <div class="artists">
                <?php foreach ($artists as $artist): ?>
                    <div class="artist">
                        <img src="<?php echo htmlspecialchars($artist['img']); ?>" alt="<?php echo htmlspecialchars($artist['artist']); ?>">
                        <p><a href="artistDetails.php?artist=<?php echo urlencode($artist['artist']); ?>"><?php echo htmlspecialchars($artist['artist']); ?></a></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
