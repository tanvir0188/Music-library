<?php
include './Backend files/indexBackend.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Streaming</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/indexStyle.css">
    <style>
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 10px;
            border: none;
            background-color: transparent;
            position: relative;
        }

        .btn::after {
            width: auto;
            height: auto;
            position: absolute;
            font-size: 15px;
            color: #00848a;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            opacity: 0;
            visibility: hidden;
            transition: .2s linear;
            top: 115%;
        }

        .icon {
            width: 30px;
            height: 30px;
            transition: .2s linear;
        }

        .icon path {
            fill: white;
            transition: .2s linear;
        }

        .btn:hover>.icon {
            transform: scale(1.2);
        }

        .btn:hover>.icon path {
            fill: #00848a;
        }

        .btn:hover::after {
            visibility: visible;
            opacity: 1;
            top: 105%;
        }

        /* New styles for favorite button */
        .btn.favorite {
            background-color: #00848a;
        }

        .btn.favorite .icon path {
            fill: yellow;

            /* Or another color to indicate it's a favorite */
        }

        .heading {
            display: flex;
            justify-content: space-between;
            padding-right: 50px;
        }
        .heading a{
            text-decoration: none;
            color: white;
        }
        .heading a:hover{
            color: #00848a;
        }
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
        <section class="hero">
            <h1>Welcome to Music Streaming</h1>
            <p>Listen to your favorite songs and podcasts.</p>
        </section>
        <section class="content">
            <section class="playlists">
                <?php
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

                <div class="heading">
                    <h2>Popular Songs</h2>
                    <a href="popularList.php">See more</a>
                </div>


                <div class="playlist-container">
                    <?php while ($row = $popularSongs->fetch_assoc()) : ?>
                        <?php $isFavorite = isset($_SESSION['userid']) && ($_SESSION['usertype']=='normal') ? isFavorite($_SESSION['userid'], $row['id'], $conn) : false; ?>
                        <div class="playlist" id="song_<?php echo $row['id']; ?>">
                            <div class="favorite-add-to-playlist-button">
                                <form method="post" action="insertFavorite.php">
                                    <input type="hidden" name="song_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                        <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                            <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z" id="Fill"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $row['id']; ?>">
                                <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                <p><?php echo htmlspecialchars($row['name']); ?></p>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>


            </section>

            <section class="popular-artists">
            <div class="heading">
                    <h2>Popular Artists</h2>
                    <a href="#">See more</a>
                </div>

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
                <div class="heading">
                    <h2>Recommended Songs</h2>
                    <a href="recommendedSongList.php">See more</a>
                </div>
                    <div class="artist-container">
                        <?php if ($preferences) : ?>
                            <?php foreach ($recommendations as $song) : ?>
                                <?php $isFavorite = isset($_SESSION['userid']) && ($_SESSION['usertype']=='normal') ? isFavorite($_SESSION['userid'], $song['id'], $conn) : false; ?>
                                <div class="artist" id="song_<?php echo $song['id']; ?>">
                                    <div class="favorite-add-to-playlist-button">
                                        <form method="post" action="insertFavorite.php">
                                            <input type="hidden" name="song_id" value="<?php echo $song['id']; ?>">
                                            <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                                                <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                                                    <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.277,2.275a3.562,3.562,0,0,0,0,5l6.475,6.556,6.475-6.556a3.563,3.563,0,0,0,0-5A3.443,3.443,0,0,0,12.786,1.25h-.01a3.415,3.415,0,0,0-2.443,1.038L8.752,3.9,7.164,2.275A3.442,3.442,0,0,0,4.72,1.25Z" id="Fill"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $song['id']; ?>">
                                        <img src="<?php echo htmlspecialchars($song['img']); ?>" alt="<?php echo htmlspecialchars($song['name']); ?>">
                                        <p><?php echo htmlspecialchars($song['name']); ?></p>
                                    </a>
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
        $popularSongs->data_seek(0); 
        while ($row = $popularSongs->fetch_assoc()) :
        ?>
            document.getElementById('song_<?php echo $row['id']; ?>').onclick = function() {
                window.location.href = 'musicPlayerAndRelatedSong.php?songId=<?php echo $row['id']; ?>';
            };
        <?php endwhile; ?>
    </script>

</body>

</html>