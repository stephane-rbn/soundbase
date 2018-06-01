<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    if (!isset($_GET["id"])) {
      die("Error: this track doesn't exist");
    } else {

      // Connection to database
      $connection = connectDB();

      $query = $connection->prepare(
        "SELECT * FROM track WHERE id='" . $_GET['id']. "'"
      );

      $query->execute();

      $track = $query->fetch(PDO::FETCH_ASSOC);
    }

  }

  include "head.php";
  include "navbar.php";
?>

    <div class="container">

      <div class="vertical-spacer"></div>

      <?php
        echo "<center>";
        echo "<h2>" . $track['title'] . "</h2>";
        echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
        echo '<audio controls>';
        echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
        echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
        echo '</center>';
        echo "<br>";
      ?>

      <h4>Description</h4>
      <p><?php echo $track["description"]; ?></p>

      <div class="vertical-spacer"></div>

    </div>


<?php
  include "footer.php";
?>
