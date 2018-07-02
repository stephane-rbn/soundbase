<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  $navbarItem = 'account';
  include "head.php";
  include "navbar.php";
?>
    <div class="wrapper" id="wrapper-login">
      <h1>RECOMMENDATIONS</h1>
      <h2>DISCOVER NEW TRACKS MADE FOR YOU</h2>
    </div>
    <div class="container">
    <div class="row justify-content-center">
    <?php

    // Connection to database
    $connection = connectDB();

    // Get the most listeneded genre (max of sum of listenings by genre)
    $mostListenedGenreQuery = $connection->prepare(
      "SELECT genre as mostListenedGenre,MAX(listenings) as listeningsOfMostListenedGenre
      FROM (
        SELECT track.genre,COUNT(*) as listenings
        FROM (
          SELECT * FROM listening WHERE member = " . $_SESSION['id'] . "
        ) as userListenings
        LEFT JOIN track
        ON track.id = UserListenings.track
        GROUP BY genre
        ORDER BY listenings DESC
      )
      as userListenedGenres;"
    );
    $mostListenedGenreQuery->execute();
    $mostListenedGenreResult = $mostListenedGenreQuery->fetch(PDO::FETCH_ASSOC);
    $mostListenedGenre = $mostListenedGenreResult['mostListenedGenre'];

    // If the user listened to at least on track
    if(!empty($mostListenedGenre)){
      // Tracks of the $mostListenedGenre that the user did not listen to yet
      $recommendedTracksQuery = $connection->prepare(
        "SELECT * FROM
        (
          SELECT * FROM listening WHERE member = " . $_SESSION['id'] . " GROUP BY track
        ) as userListenings
        LEFT JOIN track on userListenings.track = track.id
        WHERE genre = '" . $mostListenedGenre . "' AND ( userListenings.track IS NULL OR track.id IS NULL)
        UNION
        SELECT * FROM
        (
          SELECT * FROM listening WHERE member = " . $_SESSION['id'] . " GROUP BY track
        ) as userListenings
        RIGHT JOIN track on userListenings.track = track.id
        WHERE genre = '" . $mostListenedGenre . "' AND (userListenings.track IS  NULL OR track.id IS NULL);"
      );
      $recommendedTracksQuery->execute();
      $recommendedTracks = $recommendedTracksQuery->fetchAll(PDO::FETCH_ASSOC);

      echo '<div class="col-10 alert alert-primary" role="alert" style="margin: 2em 0em">';
      echo "The genre you listened the most is {$listOfGenres[$mostListenedGenre]}.<br>";
      echo "This page will show you {$listOfGenres[$mostListenedGenre]} tracks that your never listened to!";
      echo '</div>';
      echo '<div class="vertical-spacer"></div>';

      // The track number will start at 0 since we'll use it in an array
        $trackNumber = -1;
          foreach ($recommendedTracks as $track) {

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

            echo "<div class='col-lg-7 content-container'>";
            echo "<h3><a href='track.php?id={$track['id']}'>$trackArtist - {$track['title']}</a><a href='' style='color: #c8c8c8;' title='Add to a playlist' data-toggle='modal' data-target='#addToPlaylistModal-{$track['id']}'><i class='fas fa-plus fa-xs' style='margin-left: 10px;'></i></a></h3>";
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
            echo '<div class="row justify-content-center">';
            echo "<p class='col-10 alert alert-secondary'>{$track['description']}</p>";
            echo "</div>";
          echo "</div>";

          echo "<!-- Add to playlist button Modal -->";
          echo "<div class='modal fade' id='addToPlaylistModal-{$track['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
            echo "<div class='modal-dialog modal-dialog-centered' role='document'>";
              echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                  echo "<h5 class='modal-title' id='exampleModalLongTitle'>My playlists</h5>";
                  echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                    echo "<span aria-hidden='true'>&times;</span>";
                  echo "</button>";
                echo "</div>";
                echo "<div class='modal-body'>";

                  $getAllPlaylistsQuery = $connection->prepare(
                    "SELECT * FROM playlist WHERE member='" . $_SESSION['id']. "'"
                  );

                  $getAllPlaylistsQuery->execute();

                  $allPlaylists = $getAllPlaylistsQuery->fetchAll(PDO::FETCH_ASSOC);

                  if (count($allPlaylists) === 0) {
                    echo "<h3>No playlist created. <a href='newPlaylist.php'>Create one!</a></h3>";
                  } else {
                    foreach($allPlaylists as $playlist) {
                      echo "<h3><a href='script/addToPlaylist.php?playlist_id=" . $playlist["id"] . "&track_id=" . $track['id'] . "'>" . $playlist["name"] . "</a></h3>";
                      echo "<hr>";
                    }
                  }
                echo "</div>";
                echo "<div class='modal-footer'>";
                  echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                echo "</div>";
              echo "</div>";
            echo "</div>";
          echo "</div>";
          }
    } else {
      // The user did not listen to any track
      ?>
      <div class="alert alert-warning" role="alert">
        You did not listen to any track yet.
      </div>
      <?php
    }
    ?>
    </div>
    </div>

    <div class="vertical-spacer"></div>

<?php
  include "footer.php";
?>
