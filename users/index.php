<?php
session_start();
require '../db.php'; // Update the path if necessary

// Function to fetch popular songs
function fetchPopularSongs($limit)
{
    global $conn;
    $query = "SELECT id, name, img FROM songs ORDER BY popularity DESC LIMIT $limit";
    return $conn->query($query);
}

// Function to fetch popular artists
function fetchPopularArtists($limit)
{
    global $conn;
    $query = "SELECT artist, MAX(img) as img FROM songs GROUP BY artist ORDER BY SUM(popularity) DESC LIMIT $limit";
    return $conn->query($query);
}

function fetchUserPreferences($userId)
{
    global $conn;
    $query = "SELECT * FROM preferences WHERE user_id = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function fetchSongs()
{
    global $conn;
    $query = "SELECT * FROM songs";
    return $conn->query($query);
}

function normalizeLoudness($loudness)
{
    return ($loudness + 60) / 60;
}

function normalizeInstrumentalness($instrumentalness)
{
    return log1p($instrumentalness);
}

function calculateSimilarity($song, $preferences)
{
    return sqrt(
        pow($song['danceability'] - $preferences['danceability'], 2) +
            pow($song['energy'] - $preferences['energy'], 2) +
            pow($song['loudness'] - normalizeLoudness($preferences['loudness']), 2) +
            pow($song['speechiness'] - $preferences['speechiness'], 2) +
            pow($song['acousticness'] - $preferences['acousticness'], 2) +
            pow($song['instrumentalness'] - normalizeInstrumentalness($preferences['instrumentalness']), 2) +
            pow($song['liveness'] - $preferences['liveness'], 2) +
            pow($song['valence'] - $preferences['valence'], 2)
    );
}

$userLoggedIn = isset($_SESSION['userid']);
$recommendations = [];

if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
    $preferences = fetchUserPreferences($userId);
    $songs = fetchSongs();

    if ($preferences) {
        while ($song = $songs->fetch_assoc()) {
            $song['loudness'] = normalizeLoudness($song['loudness']);
            $song['instrumentalness'] = normalizeInstrumentalness($song['instrumentalness']);
            $similarity = calculateSimilarity($song, $preferences);
            $song['similarity'] = $similarity;
            $recommendations[] = $song;
        }

        usort($recommendations, function ($a, $b) {
            return $a['similarity'] <=> $b['similarity'];
        });

        $recommendations = array_slice($recommendations, 0, 5);
    }
}


// Fetch top 5 popular songs
$popularSongs = fetchPopularSongs(5);
// Fetch top 5 popular artists
$popularArtists = fetchPopularArtists(5);

// Check if user is logged in
$userLoggedIn = isset($_SESSION['userid']);
$preferences = null;
if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
    $preferences = fetchUserPreferences($userId);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Streaming</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/indexStyle.css">
</head>

<body>
    <header>
        <div class="logo">Music Streaming</div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="Details.php">Your Library</a></li>

            </ul>
            <form action="search_action.php" class="search-form" method="GET">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

        </nav>
        <div class="auth-buttons">
            <?php if (!$userLoggedIn) : ?>
                <button class="login-btn" onclick="window.location.href='../loginAnd signup.html'">Login</button>
                <button class="signup-btn" onclick="window.location.href='../loginAnd signup.html'">Sign Up</button>
            <?php else : ?>
                <span>Welcome, <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></span>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <section class="hero">
            <h1>Welcome to Music Streaming</h1>
            <p>Listen to your favorite songs and podcasts.</p>
        </section>
        <section class="content">
            <section class="playlists">
                <h2>Popular Songs</h2>
                <div class="playlist-container">
                    <?php while ($row = $popularSongs->fetch_assoc()) : ?>
                        <div class="playlist" id="song_<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                            <p><?php echo htmlspecialchars($row['name']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
            <section class="popular-artists">
                <h2>Popular Artists</h2>
                <div class="artist-container">
                    <?php while ($row = $popularArtists->fetch_assoc()) : ?>
                        <div class="artist" id="artist_<?php echo urlencode($row['artist']); ?>">
                            <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Artist Image">
                            <p><a href="artistDetails.php?artist=<?php echo urlencode($row['artist']); ?>"><?php echo htmlspecialchars($row['artist']); ?></a></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php if ($userLoggedIn) : ?>
                <section class="popular-artists">
                    <h2>Recommended Songs</h2>
                    <div class="artist-container">
                        <?php if ($preferences) : ?>
                            <?php foreach ($recommendations as $song) : ?>
                                <div class="artist" id="song_<?php echo $song['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($song['img']); ?>" alt="<?php echo htmlspecialchars($song['name']); ?>">
                                    <p><?php echo htmlspecialchars($song['name']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>Please <a href="preferenceForm.php">fill out your preferences</a> to get song recommendations.</p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
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

    <script>
        <?php
        $popularSongs->data_seek(0); // Resetting the pointer for the loop
        while ($row = $popularSongs->fetch_assoc()) :
        ?>
            document.getElementById('song_<?php echo $row['id']; ?>').onclick = function() {
                window.location.href = 'songDetails.php?songId=<?php echo $row['id']; ?>';
            };
        <?php endwhile; ?>
    </script>
</body>

</html>