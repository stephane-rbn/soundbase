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
        "SELECT * FROM playlist WHERE id='" . $_GET['id']. "'"
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

              // Get the artist name
              $trackArtistQuery = $connection->prepare(
                "SELECT name FROM member WHERE id = " . $track['member'] . ""
              );

              $likesQuery->execute();
              $isLikedQuery->execute();
              $listeningsQuery->execute();
              $trackArtistQuery->execute();

              $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
              $isLikedResult = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
              $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);
              $trackArtistResult = $trackArtistQuery->fetch(PDO::FETCH_ASSOC);

              $likes = $likesResult['likes'];
              $isLiked = $isLikedResult['liked'];
              $listeningsNumber = $listenings['listenings'];
              $trackArtist = $trackArtistResult['name'];

              echo "<center>";
              echo "<div id='playlist-{$_GET["id"]}-track-{$track["id"]}'>";
                echo "<a href='track.php?id=" . $track['id'] . "'>";
                echo "<h2>" . $track['title'] . "</h2>";
                echo "</a>";
                echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
                echo '<audio controls data-track-id="' .$track['id'] . '" id="audio-track-' . $trackNumber . '" >';
                echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
                echo '</audio><br> Artist: ' . $trackArtist . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
                echo '<hr>';
                echo '<i class="fas fa-play"></i>';
                echo '<span class="listeningsNumber" id="listenings-number-' .$track['id'] . '">' .$listeningsNumber . '</span>';
                echo '<span class="likes" id="likes-' .$track['id'] . '" onclick="likeTrack('. $track['id'] . ')">';
                echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                echo '<span class="likeNumber" id="likeNumber-' .$track['id'] . '">' .$likes . '</span>';
                echo '</span>';
                if ($playlistName["member"] === $_SESSION["id"]) {
                  echo '<a href="" style="color: #c8c8c8;" title="Delete track" data-toggle="modal" data-target="#deleteTrackModal-' . $track["id"] . '">';
                  echo '<button type="button" class="btn btn-danger delete-button"><i class="fas fa-trash-alt"></i></button>';
                  echo '</a>';
                }
              echo "</div>";
              echo '</center>';
              echo "<br>";

              echo "<!-- Delete track button modal -->";
              echo "<div class='modal fade' id='deleteTrackModal-{$track['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
                echo "<div class='modal-dialog modal-dialog-centered' role='document'>";
                  echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                      echo "<h5 class='modal-title' id='exampleModalLongTitle'>Are you sure you want to remove the track from this playlist?</h5>";
                      echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                        echo "<span aria-hidden='true'>&times;</span>";
                      echo "</button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<button type='button' class='btn btn-danger delete-button' data-dismiss='modal' onclick='removeTrackFromPlaylist({$track['id']}, {$_GET['id']});'>Delete</button>";
                    echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                    echo "</div>";
                  echo "</div>";
                echo "</div>";
              echo "</div>";
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
