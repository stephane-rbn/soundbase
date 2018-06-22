<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    if (!isset($_GET["id"])) {
      header("Location: myPlaylists.php");
    } else {

      // Connection to database
      $connection = connectDB();

      $getAllTracksQuery = $connection->prepare(
        "SELECT * FROM track,inclusion WHERE inclusion.track=track.id AND inclusion.playlist='" . $_GET['id']. "'"
      );

      $getPlaylistNameQuery = $connection->prepare(
        "SELECT name FROM playlist WHERE id='" . $_GET['id']. "'"
      );

      $getAllTracksQuery->execute();
      $getPlaylistNameQuery->execute();

      $tracks = $getAllTracksQuery->fetchAll(PDO::FETCH_ASSOC);
      $playlistName = $getPlaylistNameQuery->fetch(PDO::FETCH_ASSOC);
    }

  }

  include "head.php";
  include "navbar.php";
?>


    <div class="vertical-spacer"></div>

    <div class="container">
      <button onclick="history.go(-1);" class="btn btn-secondary">Back</button>

      <br>
      <br>

      <h1 class="text-center"><?php echo $playlistName["name"]; ?></h1>

      <br>
      <br>

        <?php
          // The track number will start at 0 since we'll use it in an array
          $trackNumber = -1;

          if (count($tracks) !== 0) {
            foreach ($tracks as $track) {

              // Increment track id for DOM
              $trackNumber++;

              // Get the number of listenings
              $listeningsQuery = $connection->prepare(
                "SELECT COUNT(*) as listenings FROM listening WHERE track=" . $track['id']
              );

              // Get the number of likes
              $likesQuery = $connection->prepare(
                "SELECT COUNT(*) as likes FROM likes WHERE track=" . $track['id']
              );

              // Check if the track is liked by the user
              $isLikedQuery = $connection->prepare(
                "SELECT COUNT(*) as liked FROM likes WHERE track='" . $track['id'] . "' AND member='" . $_SESSION['id'] ."'"
              );

              $likesQuery->execute();
              $isLikedQuery->execute();
              $listeningsQuery->execute();

              $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
              $isLikedResult = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
              $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);

              $likes = $likesResult['likes'];
              $isLiked = $isLikedResult['liked'];
              $listeningsNumber = $listenings['listenings'];

              echo "<center>";
              echo "<a href='track.php?id=" . $track['id'] . "'>";
              echo "<h2>" . $track['title'] . "</h2>";
              echo "</a>";
              echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
              echo '<audio controls data-track-id="' .$track['id'] . '" id="audio-track-' . $trackNumber . '" >';
              echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
              echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
              echo '<hr>';
              echo '<i class="fas fa-play"></i>';
              echo '<span class="listeningsNumber" id="listenings-number-' .$track['id'] . '">' .$listeningsNumber . '</span>';
              echo '<span class="likes" id="likes-' .$track['id'] . '" onclick="likeTrack('. $track['id'] . ')">';
              echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
              echo '<span class="likeNumber" id="likeNumber-' .$track['id'] . '">' .$likes . '</span>';
              echo '</span>';
              echo '</center>';
              echo "<br>";
            }
          } else {
            echo "<center>";
              echo "No track added in this playlist.";
            echo "</center>";
          }
        ?>

    </div>

    <div class="vertical-spacer"></div>

<?php
  include "footer.php";
?>
