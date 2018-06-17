<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-login">
      <h1>HOME</h1>
      <h2>LISTEN TO YOUR FAVORITE ARTISTS</h2>
    </div>
    <div>
      <div class="home-feed">
        <?php
          $trackData = sqlSelectFetchAll('SELECT * FROM track WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ") ORDER BY publication_date DESC");

          if (!empty($trackData)) {

            foreach ($trackData as $track) {

              $artistQuery = sqlSelect("SELECT name FROM member WHERE ID = ".$track['member']);

              $artist = $artistQuery['name'];

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
              echo '</div>';
            }

          } else {
            echo "<p class='empty-home'>You can search for a member to follow his news</p>";
          }
        ?>
      </div>
    </div>

<?php
  include "footer.php";
?>
