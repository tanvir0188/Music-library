<?php
session_start();
$searchResults = isset($_SESSION['search_results']) ? $_SESSION['search_results'] : [];
unset($_SESSION['search_results']);
$userLoggedIn = isset($_SESSION['userid']);
if ($userLoggedIn) {
    $userId = $_SESSION['userid'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .search-results table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .search-results td {
            padding: 10px;
            text-align: center;
            width: 50%;
        }

        .search-results th {
            background-color: #00848a;
            color: #fff;
        }

        .search-results tbody tr {
            background-color: #121212;
        }

        .search-results tbody tr:hover {
            background-color: #333;
        }

        .search-results tbody td img {
            width: 50px;
            height: 50px;
        }

        .search-results audio {
            width: 100%;
            max-width: 200px;
        }

        .song-td {
            display: flex;
        }

        .song-td .content {
            text-align: start;
            margin-left: 5px;

            a {
                color: white;
            }

            a:hover {
                color: #00848a;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">Music Streaming</div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="Details.php">Your Library</a></li>
                <li><a href="profile.php">More</a></li>
                <li><a href="preferenceForm.php">Edit or Add preference</a></li>
            </ul>
            <form action="search_action.php" class="search-form" method="GET">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </nav>
        <div class="auth-buttons">
            <?php if (!$userLoggedIn) : ?>
                <button class="login-btn" onclick="window.location.href='login.html'">Login</button>
                <button class="signup-btn" onclick="window.location.href='signup.html'">Sign Up</button>
            <?php else : ?>
                <span>Welcome, <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></span>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <section class="search-results">
            <h1>Search Results</h1>
            <?php if (!empty($searchResults)) : ?>
                <table>
                    <tbody>
                        <?php foreach ($searchResults as $song) : ?>
                            <tr>
                                <td style="text-align: start;">
                                    <div class="song-td">
                                        <div class="image">
                                            <img src="<?php echo htmlspecialchars($song['img']); ?>" alt="<?php echo htmlspecialchars($song['name']); ?>">
                                        </div>
                                        <div class="content">
                                            <b><a href="musicPlayerAndRelatedSong.php?songId=<?php echo $song['id']; ?>"><?php echo htmlspecialchars($song['name']); ?></a></b><br>
                                            <a href="artistDetails.php?artist=<?php echo urlencode($song['artist']); ?>"><?php echo htmlspecialchars($song['artist']); ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: end;">
                                    <?php if ($song['preview']) : ?>
                                        <audio controls>
                                            <source src="<?php echo htmlspecialchars($song['preview']); ?>" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>
                                    <?php else : ?>
                                        No preview available
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            <?php else : ?>
                <p>No results found for your search query.</p>
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
</body>

</html>