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

          // Get all tracks ordered by listening count
          $trackData = sqlSelectFetchAll(
            "SELECT track.*,COUNT(listening.track) as listenings FROM track
            LEFT JOIN listening ON track.id = listening.track
            GROUP BY track.id ORDER BY listenings DESC");

          if (!empty($trackData)) {

            foreach ($trackData as $track) {

              $artistQuery = sqlSelect(
                "SELECT name FROM member WHERE id = ".$track['member']
              );
              $artist = $artistQuery['name'];

              $likesQuery = sqlSelect(
                "SELECT COUNT(*) as likes FROM likes WHERE track=" . $track['id']
              );
              $likes = $likesQuery['likes'];

              $isLikedQuery = sqlSelect(
                "SELECT COUNT(*) as liked FROM likes WHERE track='" . $track['id'] . "' AND member='" . $_SESSION['id'] ."'"
              );
              $isLiked = $isLikedQuery['liked'];

              echo '<div class="track-wrapper">';
                echo "<a href='track.php?id=" . $track['id'] . "'>";
                  echo "<h2>" . $artist . " - " .$track['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/tracks/album_cover/'. $track['photo_filename'] . '">';
                  echo '<div class="track-content">';
                    echo '<audio controls>';
                    echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
                    echo '</audio>';
                    echo '<h3 class="track-info"> '. $track['genre'] . ' - ' . $track['publication_date'] . '</span>';
                  echo '</div>';
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
