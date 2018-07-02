<?php
  session_start();

  require_once "functions.php";

  xssProtection();

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
  <body onload="getComments('track',<?php echo $_GET['id'] ?>)">
    <div class="container">

      <div class="vertical-spacer"></div>

          <div class="row justify-content-center">

          <?php
            // Track id for DOM
            $trackNumber = 0;

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

            // Get the artist name
            $authorQuery = $connection->prepare(
              "SELECT name FROM member WHERE id = " . $track['member'] . ""
            );

            $likesQuery->execute();
            $isLikedQuery->execute();
            $listeningsQuery->execute();
            $authorQuery->execute();

            $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
            $isLikedResult = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
            $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);
            $authorResult = $authorQuery->fetch(PDO::FETCH_ASSOC);

            $likes = $likesResult['likes'];
            $isLiked = $isLikedResult['liked'];
            $listeningsNumber = $listenings['listenings'];
            $author = $authorResult['name'];

            echo "<div class='col-lg-6 content-container'>";
              echo "<h3>$author - {$track['title']}<a href='' style='color: #c8c8c8;' title='Add to a playlist' data-toggle='modal' data-target='#addToPlaylistModal'><i class='fas fa-plus fa-xs' style='margin-left: 10px;'></i></a></h3>";
              echo "<div><img class='content-image' src='uploads/tracks/album_cover/{$track['photo_filename']}'></div>";
              echo "<audio controls id='audio-track-$trackNumber' data-track-id='{$track['id']}'>";
                echo "<source src='uploads/tracks/files/{$track['track_filename']}' type='audio/mpeg'>";
              echo "</audio>";
              echo "<p><i class='fas fa-calendar-alt'></i> {$track['publication_date']}</p>";
              echo "<p>";
              echo "<span class='track-listenings'><i class='fas fa-play'></i>";
              echo "<span class='listening-number' id='listening-number-{$track['id']}'>$listeningsNumber</span>";
              echo "</span>";
              echo "<span class='track-likes' id='likes-{$track['id']}' onclick='likeTrack({$track['id']})'>";
              echo "<i class='" . (($isLiked == 1) ? 'fas' : 'far') . " fa-heart'></i>";
                echo "<span class='like-number' id='like-number-{$track['id']}'>$likes</span>";
              echo "</span>";
              echo "<p class='alert alert-secondary'>{$track['description']}</p>";
            echo "</div>";
          ?>
        </div>
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

      <div class="container center_div register-form">

        <div class="col-sm-12">
          <div class="form-group">
              <label for="content">Comment (280 characters max):</label>
              <textarea name="comment" rows="5" onkeyup="displayTextareaLength(280);" id="comment-content" class="form-control" placeholder ="Your comment..."></textarea>
              <p id="textarea-counter"></p>
              <button class="btn btn-secondary" onclick="addComment('track',<?php echo $_GET['id'] ?>)" >Submit</button>
          </div>
        </div>

      <div id="comments">
      </div>
    </div>
  </div>
  </div>
</body>

<?php
  include "footer.php";
?>
