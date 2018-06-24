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
          $feedData = sqlSelectFetchAll('(SELECT id, title, photo_filename as cover, publication_date, genre as info, track_filename as content, member FROM track WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) UNION (SELECT id, name as title, background_filename as cover, publication_date, address as info, event_date as content, member FROM events WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) ORDER BY `publication_date` DESC');

          //Display tracks, posts and events when you follow members
          if (!empty($feedData)) {

            foreach ($feedData as $feedEntry) {

              $artistQuery = sqlSelect(
                "SELECT name FROM member WHERE id = ".$feedEntry['member']
              );
              $artist = $artistQuery['name'];

              $likesQuery = sqlSelect(
                "SELECT COUNT(*) as likes FROM likes WHERE track=" . $feedEntry['id']
              );
              $likes = $likesQuery['likes'];

              $isLikedQuery = sqlSelect(
                "SELECT COUNT(*) as liked FROM likes WHERE track='" . $feedEntry['id'] . "' AND member='" . $_SESSION['id'] ."'"
              );
              $isLiked = $isLikedQuery['liked'];

              //recover the extension
              $extension = substr( strrchr($feedEntry['content'], '.')  ,1);

              //if extension is "mp3" display track else display a event
              if($extension === "mp3" ){

              echo '<div class="track-wrapper">';
                echo "<h2>Track :</h2>";
                echo "<a href='track.php?id=" . $feedEntry['id'] . "'>";
                  echo "<h2>" . $artist . " - " .$feedEntry['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/tracks/album_cover/'. $feedEntry['cover'] . '">';
                  echo '<div class="track-content">';
                    echo '<audio controls>';
                    echo '<source src="uploads/tracks/files/' . $feedEntry['content'] . '" type="audio/flac">';
                    echo '</audio>';
                    echo '<h3 class="track-info"> '. $feedEntry['info'] . ' - ' . $feedEntry['publication_date'] . '</span>';
                  echo '</div>';
                  echo '<span class="likes" id="likes-' .$feedEntry['id'] . '" onclick="likeTrack('. $feedEntry['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$feedEntry['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
              }else{
              echo '<div class="track-wrapper">';
                echo "<h2>Event :</h2>";
                echo "<a href='event.php?id=" . $feedEntry['id'] . "'>";
                  echo "<h2>" . $artist . " - " .$feedEntry['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/events/backgrounds/'. $feedEntry['cover'] . '">';
                  echo '<div class="track-content">';
                    echo '<div class="track-content">';
                      echo '<h2>' . $feedEntry['content'] . '</h2>';
                    echo '</div>';
                    echo '<div class="track-info">';
                      echo '<h3> '. $feedEntry['info'].'</h3>';
                      echo '<h3> '. $feedEntry['publication_date'].'</h3>';
                    echo '</div>';
                  echo '</div>';
                  echo '<span class="likes" id="likes-' .$feedEntry['id'] . '" onclick="likeTrack('. $feedEntry['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$feedEntry['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
              }

            }
            //if you follow nobody
          } else {
            echo "<p class='empty-home'>You can search for a member to follow his feedEntry</p>";
          }
        ?>
      </div>
    </div>

<?php
  include "footer.php";
?>
