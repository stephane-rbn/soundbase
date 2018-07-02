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
    <div class="vertical-spacer"></div>
    <div class="container">
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

      echo '<div class="alert alert-primary" role="alert">';
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

            echo "<center>";
              echo "<a href='track.php?id=" . $track['id'] . "'>";
              echo "<h2>" . $track['title'] . "</h2>";
              echo "</a>";
              echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
              echo '<audio controls data-track-id="' .$track['id'] . '" id="audio-track-' . $trackNumber . '" >';
              echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
              echo '</audio><br> Artist: ' . $trackArtist . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
              echo '<hr>';
              echo '<i class="fas fa-play"></i>';
              echo '<span class="listeningsNumber" id="listening-number-' .$track['id'] . '">' .$listeningsNumber . '</span>';
              echo '<span class="likes" id="likes-' .$track['id'] . '" onclick="likeTrack('. $track['id'] . ')">';
              echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
              echo '<span class="likeNumber" id="likeNumber-' .$track['id'] . '">' .$likes . '</span>';
              echo '</span>';
            echo '</center>';
            echo "<br>";
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

    <div class="vertical-spacer"></div>

<?php
  include "footer.php";
?>
