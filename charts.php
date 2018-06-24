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
    <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Artist</th>
        <th scope="col">Listenings</th>
      </tr>
    </thead>
    <tbody>
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

      $trackRank = 0;

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

          // Increment track rank
          $trackRank++;

          echo '<tr>';
            echo '<th scope="row">' . $trackRank . '</th>';
            echo "<a href='track.php?id=" . $track['id'] . "'>";
            echo '<td><a href="track.php?id=' . $track['id'] . '">' . $track['title'] . '</a></td>';
            echo '<td>' . $trackArtist . '</td>';
            echo '<td>' . $listeningsNumber . '</td>';
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
