<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    if (!isset($_GET["id"])) {
      die("Error: this track doesn't exist");
    } else {

      // Connection to database
      $connection = connectDB();

      $query = $connection->prepare(
        "SELECT * FROM track WHERE id='" . $_GET['id']. "'"
      );

      $query->execute();

      $track = $query->fetch(PDO::FETCH_ASSOC);
    }

  }

  include "head.php";
  include "navbar.php";
?>

    <div class="container">

      <div class="vertical-spacer"></div>

      <?php
        echo "<center>";
        // echo "<h2>" . $track['title'] . "</h2>";
        echo "<h2><a href='track.php?id=" . $track['id'] . "'>" . $track['title'] . "</a><a href='' style='color: #c8c8c8;' title='Add to a playlist' data-toggle='modal' data-target='#addToPlaylistModal'><i class='fas fa-plus fa-xs' style='margin-left: 10px;'></i></a></h2>";
        echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
        echo '<audio controls>';
        echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
        echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
        echo '</center>';
        echo "<br>";
      ?>

      <!-- Add to playlist button Modal -->
      <div class="modal fade" id="addToPlaylistModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">My playlists</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <?php
                $getAllPlaylistsQuery = $connection->prepare(
                  "SELECT * FROM playlist WHERE member='" . $_SESSION['id']. "'"
                );

                $getAllPlaylistsQuery->execute();

                $allPlaylists = $getAllPlaylistsQuery->fetchAll(PDO::FETCH_ASSOC);

                foreach($allPlaylists as $playlist) {
                  echo "<h3><a href='script/addToPlaylist.php?playlist_id=" . $playlist["id"] . "&track_id=" . $track['id'] . "'>" . $playlist["name"] . "</a></h3>";
                  echo "<hr>";
                }
              ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <h4>Description</h4>
      <p><?php echo $track["description"]; ?></p>

      <div class="vertical-spacer"></div>

    </div>


<?php
  include "footer.php";
?>
