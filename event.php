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

      // Query that gets all registration
      $getRegisteredPeopleQuery = $connection->prepare(
        "SELECT * FROM registration WHERE registration.events=" . $_GET["id"]
      );

      $getRegisteredPeopleQuery->execute();

      $attendees = $getRegisteredPeopleQuery->fetchAll(PDO::FETCH_ASSOC);
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

    <div class="container-fluid"><?php successfulUpdateMessage(); ?></div>

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
          echo "<a href='profile.php?username=" . $creator["username"] . "'>" . $creator["name"] . "</a>";
        echo "</p>";
        echo '<a href="#" style="color: inherit" data-toggle="modal" data-target="#exampleModalCenter">5 places left out of ' . $event["capacity"] . '</a>';
      ?>

        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Attendees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php
                  $listQuery = $connection->prepare("SELECT * FROM member WHERE id=?");

                  foreach ($attendees as $attendee) {
                    $listQuery->execute([$attendee["member"]]);
                    $result = $listQuery->fetch(PDO::FETCH_ASSOC);
                    echo "<div class='row'>";
                      echo "<div class='col-md-2'>";
                        echo "<img src='uploads/member/avatar/" . $result["profile_photo_filename"] . "' alt='profile picture' height=50 width=50>";
                      echo "</div>";
                      echo "<div class='col-md-10'>";
                        echo "<h5><a href='profile.php?username=" . $result["username"] . "'>" . $result["name"] . "</a></h5>";
                      echo "</div>";
                    echo "</div>";
                    echo "<hr>";
                  }
                ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

      <?php
        echo "<br>";
        echo "<br>";

        $isAttendee = false;

        foreach($attendees as $attendee) {
          if($attendee["member"] === $_SESSION["id"] && $attendee["events"] === $_GET["id"]) {
            $isAttendee = true;
          }
        }

        if ($isAttendee) {
          echo "<center>";
            echo "<form method='POST' action='script/cancelEventAttendance.php'>";
              echo "<input name='event_id' value='" . $_GET["id"] . "' hidden>";
              echo "<button type='submit' class='btn btn-warning'>Cancel</button>";
            echo "</form>";
          echo "</center>";
        } else {
          echo "<center>";
            echo "<form method='POST' action='script/registerInEvent.php'>";
              echo "<input name='event_id' value='" . $_GET["id"] . "' hidden>";
              echo "<button type='submit' class='btn btn-primary'>Attend</button>";
            echo "</form>";
          echo "</center>";
        }
        echo "</center>";

        echo "<hr>";
        echo "<br>";
        echo "<h4>Description</h4>";
        echo "<p>" . $event["description"] . "</p>";
    ?></div>

    <div class="vertical-spacer"></div>

    <?php
      unset($_SESSION["registredInEvent"]);
      unset($_SESSION["cancelledAttendance"]);
    ?>

<?php
  include "footer.php";
?>