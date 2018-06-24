<?php
  session_start();

  require_once "functions.php";

  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-login">
      <h1>CHARTS</h1>
      <h2>LISTEN TO THE BEST HITS</h2>
    </div>
    <div>
      <div class="home-feed">
        <?php

        // Connection to database
        $connection = connectDB();

          // Get all tracks ordered by listening count
          $getTracksQuery = $connection->prepare(
            "SELECT track.*,COUNT(listening.track) as listenings FROM track
            LEFT JOIN listening ON track.id = listening.track
            GROUP BY track.id ORDER BY listenings DESC");

          $getTracksQuery->execute();

          $tracks = $getTracksQuery->fetchAll(PDO::FETCH_ASSOC);

          // The track number will start at 0 since we'll use it in an array
          $trackNumber = -1;

          if (!empty($tracks)) {

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

              echo '<div class="track-wrapper">';
                echo "<a href='track.php?id=" . $track['id'] . "'>";
                  echo "<h2>" . $trackArtist . " - " .$track['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/tracks/album_cover/'. $track['photo_filename'] . '">';
                  echo '<div class="track-content">';
                    echo '<audio controls data-track-id="' .$track['id'] . '" id="audio-track-' . $trackNumber . '" >';
                    echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
                    echo '</audio>';
                    echo '<h3 class="track-info"> '. $track['genre'] . ' - ' . $track['publication_date'] . '</span>';
                  echo '</div>';
                  echo '<i class="fas fa-play"></i>';
                  echo '<span class="listeningsNumber" id="listenings-number-' .$track['id'] . '">' .$listeningsNumber . '</span>';
                  echo '<span class="likes" id="likes-' .$track['id'] . '" onclick="likeTrack('. $track['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$track['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
            }
          } else {
            echo "<p class='empty-home'>There are no tracks yet...</p>";
          }
        ?>
      </div>
    </div>

<?php
  include "footer.php";
?>
