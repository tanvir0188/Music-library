<?php
// Include your backend files here if needed

// Assuming you have a connection to your database already established
// Fetch popular songs data from your database or any other source
// Example:
// $popularSongs = $conn->query("SELECT * FROM popular_songs");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Songs</title>
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        /* Add any additional styles if needed */
    </style>
</head>

<body>
    <header>
        <div class="logo">Music Streaming</div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="Details.php">Your Library</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <!-- Add authentication buttons if needed -->
        </div>
    </header>
    <main>
        <section class="popular-songs">
            <div class="heading">
                <h2>Popular Songs</h2>
            </div>
            <div class="playlist-container">
                <!-- Loop through your popular songs data and display them -->
                <?php while ($row = $popularSongs->fetch_assoc()) : ?>
                    <div class="playlist">
                        <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <p><?php echo htmlspecialchars($row['name']); ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
    <footer>
        <div class="footer-section">
            <h3>Company</h3>
            <ul>
                <li><a href="#">About</a></li>
                <li><a href="#">Jobs</a></li>
                <li><a href="#">For the Record</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Communities</h3>
            <ul>
                <li><a href="#">For Artists</a></li>
                <li><a href="#">Developers</a></li>
                <li><a href="#">Advertising</a></li>
                <li><a href="#">Investors</a></li>
                <li><a href="#">Vendors</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Useful links</h3>
            <ul>
                <li><a href="#">Support</a></li>
                <li><a href="#">Free Mobile App</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Spotify Plans</h3>
            <ul>
                <li><a href="#">Premium Individual</a></li>
                <li><a href="#">Premium Duo</a></li>
                <li><a href="#">Premium Family</a></li>
                <li><a href="#">Premium Student</a></li>
                <li><a href="#">Spotify Free</a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Spotify AB</p>
        </div>
    </footer>
</body>

</html>
