<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    if (!isset($_GET["id"])) {
      header("Location: myPlaylists.php");
    } else {

      // Connection to database
      $connection = connectDB();

      // Query that gets this event's info
      $query = $connection->prepare(
        "SELECT * FROM events WHERE id='" . $_GET['id']. "'"
      );

      $query->execute();

      $event = $query->fetch(PDO::FETCH_ASSOC);

      // Query that gets the creator's info
      $getCreatorQuery = $connection->prepare(
        "SELECT * FROM member WHERE id=" . $event["member"]
      );

      $getCreatorQuery->execute();

      $creator = $getCreatorQuery->fetch(PDO::FETCH_ASSOC);
    }

  }

  include "head.php";
  include "navbar.php";
?>

    <!-- Header - set the background image for the header in the line below -->
    <header class="py-5 bg-image-full" style="height: 400px; background-image: url('<?php
        if ($event["background_filename"] !== "background.png") {
          echo "uploads/events/backgrounds/" . $event["background_filename"];
        } else {
          echo "http://via.placeholder.com/1000x500";
        }
      ?>');" alt="event-background">
    </header>

    <div style="height: 2em;"></div>

    <div class='container'>
    <?php
      echo "<center>";
        echo "<div>";
          echo "<h1>" . $event["name"] . "</h1>";
          echo "<span class='badge badge-success'>Not full</span>";
          // echo "<span class='badge badge-warning'>Full</span>";
        echo "</div>";
        echo "<br>";
        echo "<p class='lead'>Created by ";
          echo "<a href='profile.php?username=" . $creator["username"] ."'>" . $creator["name"] . "</a>";
        echo "</p>";
        echo '5 places left out of ' . $event["capacity"];
        echo "<hr>";
        echo "<br>";
        echo "<p>" . $event["description"] . "</p>";
      echo "</center>";
    ?></div>

    <div class="vertical-spacer"></div>


<?php
  include "footer.php";
?>
