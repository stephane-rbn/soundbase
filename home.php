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
          $newsData = sqlSelectFetchAll('(SELECT id, title, photo_filename as cover, publication_date, genre as info, track_filename as content, member FROM track WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) UNION (SELECT id, name as title, background_filename as cover, publication_date, address as info, event_date as content, member FROM events WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) ORDER BY `publication_date` DESC');

          //Display tracks, posts and events when you follow members
          if (!empty($newsData)) {

            foreach ($newsData as $news) {

              $artistQuery = sqlSelect(
                "SELECT name FROM member WHERE id = ".$news['member']
              );
              $artist = $artistQuery['name'];

              $likesQuery = sqlSelect(
                "SELECT COUNT(*) as likes FROM likes WHERE track=" . $news['id']
              );
              $likes = $likesQuery['likes'];

              $isLikedQuery = sqlSelect(
                "SELECT COUNT(*) as liked FROM likes WHERE track='" . $news['id'] . "' AND member='" . $_SESSION['id'] ."'"
              );
              $isLiked = $isLikedQuery['liked'];

              //recover the extension
              $extension = substr( strrchr($news['content'], '.')  ,1);

              //if extension is "mp3" display track else display a event
              if($extension === "mp3" ){

              echo '<div class="track-wrapper">';
                echo "<h2>Track :</h2>";
                echo "<a href='track.php?id=" . $news['id'] . "'>";
                  echo "<h2>" . $artist . " - " .$news['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/tracks/album_cover/'. $news['cover'] . '">';
                  echo '<div class="track-content">';
                    echo '<audio controls>';
                    echo '<source src="uploads/tracks/files/' . $news['content'] . '" type="audio/flac">';
                    echo '</audio>';
                    echo '<h3 class="track-info"> '. $news['info'] . ' - ' . $news['publication_date'] . '</span>';
                  echo '</div>';
                  echo '<span class="likes" id="likes-' .$news['id'] . '" onclick="likeTrack('. $news['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$news['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
              }else{
              echo '<div class="track-wrapper">';
                echo "<h2>Event :</h2>";
                echo "<a href='event.php?id=" . $news['id'] . "'>";
                  echo "<h2>" . $artist . " - " .$news['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/events/backgrounds/'. $news['cover'] . '">';
                  echo '<div class="track-content">';
                    echo '<div class="track-content">';
                      echo '<h2>' . $news['content'] . '</h2>';
                    echo '</div>';
                    echo '<div class="track-info">';
                      echo '<h3> '. $news['info'].'</h3>';
                      echo '<h3> '. $news['publication_date'].'</h3>';
                    echo '</div>';
                  echo '</div>';
                  echo '<span class="likes" id="likes-' .$news['id'] . '" onclick="likeTrack('. $news['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$news['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
              }

            }
            //if you follow nobody
          } else {
            echo "<p class='empty-home'>You can search for a member to follow his news</p>";
          }
        ?>
      </div>
    </div>

<?php
  include "footer.php";
?>
