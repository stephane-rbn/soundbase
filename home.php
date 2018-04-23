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
  <br>
  <?php
    $trackData = sqlSelect("SELECT * FROM TRACK");
    foreach ($trackData as $track) {
      echo "<center>";
      echo "<h2>".$track['title']."</h2>";
      echo '<img src="uploads/album_cover/'.$track['photo_filename'].'" height="100px">';
      echo '<audio controls>';
      echo '<source src="uploads/tracks/'.$track['track_filename'].'" type="audio/flac">';
      echo '</audio><br> Artist: '.$track['member'].'<br> Genre: '.$track['genre'].'<br> Publication: '.$track['publication_date'].'<br>';
      echo '<hr>';
      echo '</center>';
    }
  ?>
</div>

<?php
  include "footer.php";
?>
