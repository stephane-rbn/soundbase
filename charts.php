<?php
  session_start();

  require_once "functions.php";

  xssProtection();

  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-login">
      <h1>CHARTS</h1>
      <h2>LISTEN TO THE BEST HITS</h2>
    </div>
    <form action="charts.php" method="GET">

      <?php
        $allGenres = false;
        if (!isset($_GET['genre'])) {
          $allGenres = true;
        } else if ($_GET['genre'] === 'allGenres') {
          $allGenres = true;
        } else {
          $allGenres = true;
          foreach ($listOfGenres as $key => $value) {
            if ($_GET['genre'] == $key) {
              $allGenres = false;
            }
          }
        }

        $allTime = false;
        if (!isset($_GET['period'])) {
          $allTime = true;
        } else if ($_GET['period'] === 'allTime') {
          $allTime = true;
        } else {
          $allTime = true;
          foreach ($listOfPeriods as $key => $value) {
            if ($_GET['period'] == $key) {
              $allTime = false;
            }
          }
        }

        // if (isset($_GET['personal']) && $_GET['personal'] === 'on') {
        //   $personal = true;
        // } else {
        //   $personal = false;
        // }

      ?>

      <label>Genre</label>
      <select name="genre">
        <option value="all" <?php if($allGenres) echo 'selected' ?>>All</option>
        <?php
          foreach($listOfGenres as $code=>$name) {
          if(!$allGenres && $_GET['genre'] === $code) {
            $selected = "selected";
          } else {
            $selected = "";
          }
          echo "<option value='$code' $selected>$name</option>";
          }
        ?>
      </select>

      <label>Period</label>
      <select name="period">
        <option value="all" <?php if($allTime) echo 'selected' ?>>All time</option>
        <?php
          foreach($listOfPeriods as $code=>$name) {
          if(!$allTime && $_GET['period'] === $code) {
            $selected = "selected";
          } else {
            $selected = "";
          }
          echo "<option value='$code' $selected>$name</option>";
          }
        ?>
      </select>

      <?php
          // if($personal) {
          //   $checked = "checked";
          // } else {
          //   $checked = "";
          // }
          // echo "<input type='checkbox' name='personal' $checked>Personal charts</input>";
      ?>

      <input type="submit" value="Update charts">
    </form>
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

      $filter = '';

      if (isset($_GET['genre'])) {
        $exist = false;
        foreach ($listOfGenres as $key => $value) {
          if ($_GET['genre'] == $key) {
            $exist = true;
          }
        }
        if($exist) {
          $filter = "genre = '{$_GET['genre']}'";
        }
      }

      if (isset($_GET['period'])) {
        $exist = false;
        foreach ($listOfPeriods as $key => $value) {
          if ($_GET['period'] == $key) {
            $exist = true;
          }
        }
        if($exist) {
          if(!empty($filter)) {
            $filter = $filter . " AND ";
          }
          $filter = $filter . "publication_date BETWEEN DATE_SUB(NOW(), INTERVAL 1 {$_GET['period']})  AND NOW()";
        }
      }

      // if (isset($_GET['personal'])) {
      //   if($_GET['personal'] === "on") {
      //     if(!empty($filter)) {
      //       $filter = $filter . " AND ";
      //     }
      //     $filter = $filter . "listening.member = {$_SESSION['id']}";
      //   }
      // }

      if(!empty($filter)) {
        $filter = 'WHERE ' . $filter;
      }

      // Connection to database
      $connection = connectDB();

      // Get all tracks ordered by listening count
      $getTracksQuery = $connection->prepare(
        "SELECT track.*,COUNT(listening.track) as listenings FROM track
        LEFT JOIN listening ON track.id = listening.track
        $filter GROUP BY track.id ORDER BY listenings DESC");

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
            echo "<a href='track.php?id=" . $track['id'] . "'>";
            echo '<td><a href="track.php?id=' . $track['id'] . '">' . $track['title'] . '</a></td>';
            echo '<td>' . $trackArtist . '</td>';
            echo '<td>' . $track["listenings"] . '</td>';
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
