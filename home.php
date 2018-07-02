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

    <div class="container" id="home-container">
      <div class="row">
        <div class="col-lg-7 col-xs-12">
          <h2>Home feed</h2>
          <div class="row justify-content-center">

          <?php

          // Connection to database
          $connection = connectDB();

          // The track number will start at 0 since we'll use it in an array
          $trackNumber = -1;

          $feedData = sqlSelectFetchAll(
            "SELECT track.id, track.title, track.description, track.genre, track.track_filename, track.photo_filename, track.publication_date, track.member, NULL AS capacity, NULL as event_date, NULL as address, NULL as content
            FROM track WHERE MEMBER IN
            (SELECT member_followed FROM subscription WHERE member_following={$_SESSION['id']}) UNION ALL
            SELECT events.id, events.name, events.description, NULL AS genre, NULL as track_filename, events.background_filename, events.publication_date, events.member, events.capacity, events.event_date, events.address, NULL as content
            FROM events WHERE MEMBER IN
            (SELECT member_followed FROM subscription WHERE member_following={$_SESSION['id']})
            UNION ALL
            SELECT post.id, NULL as title, NULL as description, NULL as genre, NULL as track_filename, NULL as photo_filename, post.publication_date, post.member, NULL as capacity, NULL as event_date, NULL as address, post.content
            FROM post WHERE MEMBER IN
            (SELECT member_followed FROM subscription WHERE member_following={$_SESSION['id']})
            ORDER BY publication_date DESC;"
          );

          //Display tracks, posts and events
          if (!empty($feedData)) {

            foreach ($feedData as $feedEntry) {

              // Get the artist name
              $authorQuery = $connection->prepare(
                "SELECT name FROM member WHERE id = " . $feedEntry['member'] . ""
              );

              $authorQuery->execute();
              $authorResult = $authorQuery->fetch(PDO::FETCH_ASSOC);
              $author = $authorResult['name'];

              // If the entry is a track
              if(isset($feedEntry['track_filename'])){

                // Increment track id for DOM
                $trackNumber++;

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

                $likesQuery->execute();
                $isLikedQuery->execute();
                $listeningsQuery->execute();

                $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
                $isLikedResult = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
                $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);

                $likes = $likesResult['likes'];
                $isLiked = $isLikedResult['liked'];
                $listeningsNumber = $listenings['listenings'];

                echo "<div class='col-lg-10 content-container'>";
                  echo "<h3><a href='track.php?id={$feedEntry['id']}'>$author - {$feedEntry['title']}</a></h3>";
                  echo "<div><img class='content-image' src='uploads/tracks/album_cover/{$feedEntry['photo_filename']}'></div>";
                  echo "<audio controls id='audio-track-$trackNumber' data-track-id='{$feedEntry['id']}'>";
                    echo "<source src='uploads/tracks/files/{$feedEntry['track_filename']}' type='audio/mpeg'>";
                  echo "</audio>";
                  echo "<p><i class='fas fa-calendar-alt'></i> {$feedEntry['publication_date']}</p>";
                  echo "<p>";
                  echo "<span class='track-listenings'><i class='fas fa-play'></i>";
                  echo "<span class='listenings-number' id='listenings-number-{$feedEntry['id']}'>$listeningsNumber</span>";
                  echo "</span>";
                  echo "<span class='track-likes' id='likes-{$feedEntry['id']}' onclick='likeTrack({$feedEntry['id']})'>";
                  echo "<i class='" . (($isLiked == 1) ? 'fas' : 'far') . " fa-heart'></i>";
                    echo "<span class='like-number' id='like-number-{$feedEntry['id']}'>$likes</span>";
                  echo "</span>";
                  echo "<p>{$feedEntry['description']}</p>";
                echo "</div>";

              } else if(isset($feedEntry['capacity'])){

                $participantsNumberQuery = $connection->prepare(
                  "SELECT COUNT(*) as count FROM registration WHERE events=" . $feedEntry["id"]
                );

                $participantsNumberQuery->execute();
                $participantsNumberResult = $participantsNumberQuery->fetch(PDO::FETCH_ASSOC);
                $participantsNumber = $participantsNumberResult['count'];

                // If entry is event
                echo "<div class='col-lg-10 content-container'>";
                  echo "<h3><a href='event.php?id={$feedEntry['id']}'> $author - {$feedEntry['title']}</a></h3>";
                  echo "<div><img class='content-image' src='uploads/events/backgrounds/{$feedEntry['photo_filename']}'></div>";
                  echo "<p><i class='fas fa-calendar-alt'></i>{$feedEntry['publication_date']}</p>";
                  echo "<p>";
                    echo "<span class='event-users'><i class='fas fa-user'></i> $participantsNumber / {$feedEntry['capacity']}</span>";
                    echo "<span class='event-date'><i class='fas fa-calendar-check'></i> {$feedEntry['event_date']}</span>";
                  echo "</p>";
                  echo "<span class='event-location'><i class='fas fa-map-marker-alt'></i> {$feedEntry['address']}</span>";
                  echo "<p>{$feedEntry['description']}</p>";
                echo "</div>";

              } else if(isset($feedEntry['content'])){
                // If entry is post
                echo "<div class='col-lg-10 content-container'>";
                  echo "<h3><a href='post.php?id={$feedEntry['id']}'> Post from $author</a></h3>";
                  echo "<p><i class='fas fa-calendar-alt'></i> {$feedEntry['publication_date']}</p>";
                  echo "<p>{$feedEntry['content']}</p>";
                echo "</div>";
              }
            }
          } else {
            // If feed is empty
            echo "<p class='empty-home'>You can search for a member to follow his feedEntry</p>";
          }
          ?>
          </div>
        </div>
        <div class="col-lg-5 col-xs-12 home-sidebar">
        <h2>Sidebar</h2>
        <div class="row justify-content-center">
            <div class="col-10">
              <h3>Charts</h3>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Song</th>
                    <th scope="col">Artist</th>
                    <th scope="col">Listenings</th>
                  </tr>
                </thead>
                <tbody>
              <?php
              // Get all tracks ordered by listening count
              $getTracksQuery = $connection->prepare(
                "SELECT track.*,COUNT(listening.track) as listenings FROM track
                LEFT JOIN listening ON track.id = listening.track
                GROUP BY track.id ORDER BY listenings DESC LIMIT 5");

              $getTracksQuery->execute();
              $tracks = $getTracksQuery->fetchAll(PDO::FETCH_ASSOC);

              $trackRank = 0;

                foreach ($tracks as $track) {

                  // Get the artist name
                  $trackArtistQuery = $connection->prepare(
                    "SELECT name FROM member WHERE id = " . $track['member'] . ""
                  );
                  $trackArtistQuery->execute();
                  $trackArtistResult = $trackArtistQuery->fetch(PDO::FETCH_ASSOC);
                  $trackArtist = $trackArtistResult['name'];

                  // Increment track rank
                  $trackRank++;

                  echo '<tr>';
                    echo '<th scope="row">' . $trackRank . '</th>';
                    echo '<td><a href="track.php?id=' . $track['id'] . '">' . $track['title'] . '</a></td>';
                    echo '<td>' . $trackArtist . '</td>';
                    echo '<td>' . $track["listenings"] . '</td>';
                  echo '</tr>';
                }
              ?>
                </tbody>
              </table>
              <h3>Recommendations</h3>
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">Song</th>
                    <th scope="col">Artist</th>
                    <th scope="col">Genre</th>
                  </tr>
                </thead>

                <?php
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
                    WHERE genre = '" . $mostListenedGenre . "' AND (userListenings.track IS  NULL OR track.id IS NULL) LIMIT 5;"
                  );
                  $recommendedTracksQuery->execute();
                  $recommendedTracks = $recommendedTracksQuery->fetchAll(PDO::FETCH_ASSOC);

                  foreach ($recommendedTracks as $track) {
                    // Get the artist name
                    $trackArtistQuery = $connection->prepare(
                      "SELECT name FROM member WHERE id = " . $track['member'] . ""
                    );
                    $trackArtistQuery->execute();
                    $trackArtistResult = $trackArtistQuery->fetch(PDO::FETCH_ASSOC);
                    $trackArtist = $trackArtistResult['name'];

                    echo '<tr>';
                      echo '<td><a href="track.php?id=' . $track['id'] . '">' . $track['title'] . '</a></td>';
                      echo '<td>' . $trackArtist . '</td>';
                      echo "<td>{$listOfGenres[$mostListenedGenre]}</td>";
                    echo '</tr>';
                  }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php
  include "footer.php";
?>
