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
      "SELECT * FROM events"
    );

    $query->execute();

    $result = $query->fetchAll(PDO::FETCH_ASSOC);
  }

  include "head.php";
  include "navbar.php";
?>

    <div class="container">

      <div class="vertical-spacer"></div>

      <h1 class="text-center">All events</h1>

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
                    echo '<img class="card-img-top" src="http://via.placeholder.com/400x250" alt="Card image cap">';
                  } else {
                    echo '<img class="card-img-top" src="/events/backgrounds/' . $event["background_filename"] . '" alt="Card image cap">';
                  }
                  echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $event["name"] . '</h5>';
                    echo '<p class="card-text">' . $event["description"] . '</p>';
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



<?php
  include "footer.php";
?>
