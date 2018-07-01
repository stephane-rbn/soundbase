<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  $navbarItem = 'home';
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

          // Connection to database
          $connection = connectDB();

          // The track number will start at 0 since we'll use it in an array
          $trackNumber = -1;

          $feedData = sqlSelectFetchAll('(SELECT id, title, photo_filename as cover, publication_date, genre as info, track_filename as content, member FROM track WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) UNION (SELECT id, name as title, background_filename as cover, publication_date, address as info, event_date as content, member FROM events WHERE member IN (SELECT member_followed FROM subscription WHERE member_following=' . $_SESSION['id'] . ')) ORDER BY `publication_date` DESC');

          //Display tracks, posts and events when you follow members
          if (!empty($feedData)) {

            foreach ($feedData as $feedEntry) {

              // Get the number of listenings
              $listeningsQuery = $connection->prepare(
                "SELECT COUNT(*) as listenings FROM listening WHERE track=" . $feedEntry['id']
              );

              // Get the number of likes
              $likesQuery = $connection->prepare(
                "SELECT COUNT(*) as likes FROM likes WHERE track=" . $feedEntry['id']
              );

              // Check if the track is liked by the user
              $isLikedQuery = $connection->prepare(
                "SELECT COUNT(*) as liked FROM likes WHERE track='" . $feedEntry['id'] . "' AND member='" . $_SESSION['id'] ."'"
              );

              // Get the artist name
              $trackArtistQuery = $connection->prepare(
                "SELECT name FROM member WHERE id = " . $feedEntry['member'] . ""
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

              //recover the extension
              $extension = substr( strrchr($feedEntry['content'], '.')  ,1);

              //if extension is "mp3" display track else display a event
              if($extension === "mp3" ){

              // Increment track id for DOM
              $trackNumber++;

              echo '<div class="track-wrapper">';
                echo "<h2>Track :</h2>";
                echo "<a href='track.php?id=" . $feedEntry['id'] . "'>";
                  echo "<h2>" . $trackArtist . " - " .$feedEntry['title'] . "</h2>";
                echo "</a>";
                  echo '<img class="track-cover" src="uploads/tracks/album_cover/'. $feedEntry['cover'] . '">';
                  echo '<div class="track-content">';
              echo '<audio controls data-track-id="' .$feedEntry['id'] . '" id="audio-track-' . $trackNumber . '" >';
                    echo '<source src="uploads/tracks/files/' . $feedEntry['content'] . '" type="audio/flac">';
                    echo '</audio>';
                    echo '<h3 class="track-info"> '. $feedEntry['info'] . ' - ' . $feedEntry['publication_date'] . '</span>';
                  echo '</div>';
                  echo '<i class="fas fa-play"></i>';
                  echo '<span class="listeningsNumber" id="listenings-number-' .$feedEntry['id'] . '">' .$listeningsNumber . '</span>';
                  echo '<span class="likes" id="likes-' .$feedEntry['id'] . '" onclick="likeTrack('. $feedEntry['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$feedEntry['id'] . '">' .$likes . '</span>';
                  echo '</span>';
              echo '</div>';
              }else{
              echo '<div class="track-wrapper">';
                echo "<h2>Event :</h2>";
                echo "<a href='event.php?id=" . $feedEntry['id'] . "'>";
                  echo "<h2>" . $trackArtist . " - " .$feedEntry['title'] . "</h2>";
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
