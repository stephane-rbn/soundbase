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
          if (count($tracks) !== 0) {
            foreach ($tracks as $track) {
              echo "<center>";
              echo "<a href='track.php?id=" . $track['id'] . "'>";
                echo "<h2>" . $track['title'] . "</h2>";
              echo "</a>";
              echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
              echo '<audio controls>';
              echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
              echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
              echo '<hr>';
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
