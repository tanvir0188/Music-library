<?php
session_start();
require '../db.php'; 

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;


function fetchPopularSongs($offset, $limit, $conn)
{
    $query = "SELECT id, name, img, preview FROM songs ORDER BY popularity DESC LIMIT ?, ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $offset, $limit);
    $stmt->execute();
    return $stmt->get_result();
}


function isFavorite($user_id, $song_id, $conn)
{
    $query = "SELECT * FROM favorite_songs WHERE user_id = ? AND song_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $song_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}


$songs = fetchPopularSongs($offset, $limit, $conn);

while ($row = $songs->fetch_assoc()) :
    $isFavorite = isset($_SESSION['userid']) && ($_SESSION['usertype']=='normal') ? isFavorite($_SESSION['userid'], $row['id'], $conn) : false;
?>
    <tr id="song_<?php echo $row['id']; ?>">
        <td style="display: flex; align-items: center;">
            <a href="musicPlayerAndRelatedSong.php?songId=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>
            <form method="post" action="insertFavorite.php" style="margin-left: 10px;">
                <input type="hidden" name="song_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn <?php echo $isFavorite ? 'favorite' : ''; ?>">
                    <svg viewBox="0 0 17.503 15.625" height="20.625" width="20.503" xmlns="http://www.w3.org/2000/svg" class="icon">
                        <path transform="translate(0 0)" d="M8.752,15.625h0L1.383,8.162a4.824,4.824,0,0,1,0-6.762,4.679,4.679,0,0,1,6.674,0l.694.7.694-.7a4.678,4.678,0,0,1,6.675,0,4.825,4.825,0,0,1,0,6.762L8.752,15.624ZM4.72,1.25A3.442,3.442,0,0,0,2.207,2.286a3.542,3.542,0,0,0,0,4.97L8.752,13,15.3,7.256a3.542,3.542,0,0,0,0-4.97,3.545,3.545,0,0,0-4.974,0l-1.39,1.4L6.109,2.286A3.442,3.442,0,0,0,4.72,1.25Z"></path>
                    </svg>
                </button>
            </form>
        </td>
        <td><img src="<?php echo htmlspecialchars($row['img']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" width="50" height="50"></td>
        <td><audio controls><source src="<?php echo htmlspecialchars($row['preview']); ?>" type="audio/mpeg">Your browser does not support the audio element.</audio></td>
    </tr>
<?php
endwhile;
?>
