<?php
session_start();
require '../db.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['userid']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../loginAnd signup.html");
    exit();
}

// Get the search query
$searchQuery = '';
if (isset($_GET['query'])) {
    $searchQuery = htmlspecialchars($_GET['query']);
}

// Function to fetch search results based on song name or artist name
function fetchSearchResults($searchQuery) {
    global $conn;
    $query = "SELECT name, artist, img FROM songs 
              WHERE name LIKE ? OR artist LIKE ?";
    $stmt = $conn->prepare($query);
    $searchTerm = '%' . $searchQuery . '%';
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch search results
$searchResults = fetchSearchResults($searchQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="adminStyle.css">
    <style>
        .list-container {
            display: flex;
            flex-wrap: wrap;
        }

        .item-card {
            background-color: #1e1e1e;
            padding: 10px;
            margin: 10px;
            border-radius: 5px;
            text-align: center;
            flex: 1 1 calc(20% - 20px);
        }

        .item-card img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }

        .item-card h3 {
            color: #fff;
            font-size: 14px;
            margin: 5px 0;
        }

        .item-card p {
            color: #ccc;
            font-size: 12px;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="addSongForm.php">Add Song</a>
        <a href="songs.php">Song List</a>
        <a href="userList.php">User List</a>
        <a href="artistList.php">Artist List</a>
        <a href="../logout.php" style="float:right; margin-right:20px;">Logout</a>
    </nav>

    <div class="container">
        <h2>Search Results for "<?php echo $searchQuery; ?>"</h2>
        <div class="list-container">
            <?php if ($searchResults->num_rows > 0): ?>
                <?php while ($row = $searchResults->fetch_assoc()) : ?>
                    <div class="item-card">
                        <img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Item Image">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($row['artist']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No results found for "<?php echo $searchQuery; ?>"</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
