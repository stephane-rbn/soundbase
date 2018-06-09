<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {

    // Connection to database
    $connection = connectDB();

    $query = $connection->prepare(
      "SELECT * FROM playlist WHERE member='" . $_SESSION['id']. "'"
    );

    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);
  }

  include "head.php";
  include "navbar.php";
?>

    <div class="vertical-spacer"></div>

    <div class="container">
      <h1>My playlists</h1>

      <br>

      <?php
        if (count($result) === 0) {
          echo "<p>No playlist created. <a href='newPlaylist.php'>Create one!</a></p>";
        }
        foreach($result as $playlist) {
          echo "<h3><a href='playlist.php?id=" . $playlist['id'] . "'>" . $playlist["name"] . "</a></h3><button type='button' class='btn btn-danger delete-button'><a href='script/deletePlaylist.php?id=" . $playlist['id'] ."'>Delete</a></button>";
          echo "<br>";
        }
      ?>

    </div>

    <div class="vertical-spacer"></div>

<?php
  include "footer.php";
?>
