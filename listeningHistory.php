<?php
  session_start();

  require_once "functions.php";

  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-login">
      <h1>HISTORY</h1>
      <h2>EVERY TRACK YOU'VE LISTENED TO</h2>
    </div>
    <div>
    <table class="table">
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Title</th>
        <th scope="col">Artist</th>
      </tr>
    </thead>
    <tbody>
    <?php

    // Connection to database
    $connection = connectDB();

      // Get all tracks ordered by listening count
      $getTracksQuery = $connection->prepare(
        "SELECT * from track inner join listening on track.id = listening.track WHERE listening.member = 1
        ORDER BY listening_date DESC");

      $getTracksQuery->execute();

      $tracks = $getTracksQuery->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tracks as $track) {

          // Get the number of listenings
          $listeningsQuery = $connection->prepare(
            "SELECT COUNT(*) as listenings FROM listening WHERE track=" . $track['id']
          );

          // Get the artist name
          $trackArtistQuery = $connection->prepare(
            "SELECT name FROM member WHERE id = " . $track['member'] . ""
          );

          $listeningsQuery->execute();
          $trackArtistQuery->execute();

          $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);
          $trackArtistResult = $trackArtistQuery->fetch(PDO::FETCH_ASSOC);

          $listeningsNumber = $listenings['listenings'];
          $trackArtist = $trackArtistResult['name'];


          echo '<tr>';
            echo '<td>' . $track['listening_date'] . '</td>';
            echo "<a href='track.php?id=" . $track['id'] . "'>";
            echo '<td><a href="track.php?id=' . $track['id'] . '">' . $track['title'] . '</a></td>';
            echo '<td>' . $trackArtist . '</td>';
          echo '</tr>';
        }
    ?>
    </tbody>
    </table>
  </div>
</div>

<?php
  include "footer.php";
?>
