<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
      header("Location: login.php");
  } else {

    // Connection to database
      $connection = connectDB();

      if (isset($_GET["list_events"])) {
          if ($_GET["list_events"] === "all") {
              $query = $connection->prepare(
          "SELECT * FROM events"
        );
          } else {
              if ($_GET["list_events"] === "mine") {
                  $query = $connection->prepare(
            "SELECT * FROM events WHERE member = {$_SESSION["id"]}"
          );
              } else {
                  $query = $connection->prepare(
            "SELECT * FROM registration INNER JOIN events ON registration.events = events.id WHERE registration.member = {$_SESSION["id"]}"
          );
              }
          }
      } else {
          $query = $connection->prepare(
        "SELECT * FROM events"
      );
      }

      $query->execute();

      $result = $query->fetchAll(PDO::FETCH_ASSOC);
  }

  $navbarItem = 'events';
  include "head.php";
  include "navbar.php";
?>

    <div class="container">

      <br>

      <div class="container-fluid"><?php successfulUpdateMessage(); ?></div>

      <h1 class="text-center">All events</h1>

      <form method="GET">
        <select name="list_events" required>
          <option value="all">All</option>
          <option value="mine">Mine</option>
          <option value="attending">Attending</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
      </form>

      <div style="height: 2em;"></div>

      <div class="row">

        <?php
          if (!$result) {
              echo "<p>No event</p>";
          } else {
              foreach ($result as $event) {
                  echo '<div class="col-sm-12 col-md-4" style="margin-bottom: 2em;">';
                  echo '<div class="card" style="width: 100%;">';
                  if ($event["background_filename"] === 'background.png') {
                      echo '<img class="card-img-top card-events" src="http://via.placeholder.com/400x250" alt="Card image cap">';
                  } else {
                      echo '<img class="card-img-top card-events" src="uploads/events/backgrounds/' . $event["background_filename"] . '" alt="Card image cap">';
                  }
                  echo '<div class="card-body">';
                  echo '<h5 class="card-title">' . $event["name"] . '</h5>';
                  echo '<a href="event.php?id=' . $event["id"] . '" class="btn btn-primary">View</a>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
              }
          }
        ?>

      </div>

      <div class="vertical-spacer"></div>

    </div>

    <?php unset($_SESSION["newEventAdded"]); ?>

<?php
  include "footer.php";
?>
