<?php
  session_start();

  require_once "functions.php";

  xssProtection();

  // redirect to login page if not connected
  if (!isConnected()) {
      header("Location: login.php");
  } else {
      if (!isset($_GET["id"])) {
          header("Location: events.php");
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

          $following = sqlSelect("SELECT COUNT(*) as count FROM registration WHERE events=" . $_GET["id"]);
          $places = $event["capacity"] - $following["count"];
      }
  }

  $navbarItem = 'events';
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

  <body onload="getComments('event', <?php echo $_GET['id'] ?>)">
    <div style="height: 2em;"></div>

    <div class="container-fluid"><?php successfulUpdateMessage(); ?></div>

    <div class='container'>

    <?php
      echo "<center>";
        echo "<div>";
          echo "<h1>" . $event["name"] . "</h1>";
          if ($places <= 0) {
              echo "<span class='badge badge-warning'>Full</span>";
          } else {
              echo "<span class='badge badge-success'>Not full</span>";
          }
        echo "</div>";
        echo "<br>";
        echo "<p class='lead'>Created by ";
          echo "<a href='profile.php?username=" . $creator["username"] . "'>" . $creator["name"] . "</a>";
        echo "</p>";
        if ($places > 0) {
            echo '<a href="#" style="color: inherit" data-toggle="modal" data-target="#exampleModalCenter">' . $places . ' places left out of ' . $event["capacity"] . '</a>';
        } else {
            echo '<a href="#" style="color: inherit" data-toggle="modal" data-target="#exampleModalCenter">No more available places</a>';
        }
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

        foreach ($attendees as $attendee) {
            if ($attendee["member"] === $_SESSION["id"] && $attendee["events"] === $_GET["id"]) {
                $isAttendee = true;
            }
        }
        if ($creator["id"] == $_SESSION["id"]) {
            echo "<center>";
            echo "<form method='POST' action='#'>";
            echo "<input name='event_id' value='" . $_GET["id"] . "' hidden>";
            echo "<button type='button' class='btn btn-danger delete-button' data-toggle='modal' data-target='#deleteModal'>Delete</button>";
            echo "</form>";
            echo "</center>";
        } elseif ($isAttendee) {
            echo "<center>";
            echo "<form method='POST' action='script/cancelEventAttendance.php'>";
            echo "<input name='event_id' value='" . $_GET["id"] . "' hidden>";
            echo "<button type='submit' class='btn btn-warning'>Cancel</button>";
            echo "</form>";
            echo "<br>";
            echo '<a href="script/createTicket.php?id=' . $_GET['id'] . '" style="color: inherit;">';
            echo '<i class="far fa-file-pdf fa-lg" title="Download your entry ticket!"></i>';
            echo '</a>';
            echo "</center>";
        } elseif ($places <= 0) {
            echo "<center>";
            echo "<div class='form_message_error'>Sorry this event is full</div>";
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
        echo "<center>";
        echo "<h4>Description</h4>";
        echo "<p class='col-6 alert alert-secondary'>{$event['description']}</p>";
        echo "</center>"
    ?>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              The deletion of an event is irreversible.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger delete-button">
                <a href="script/deleteEvent.php?id=<?php echo $event["id"]; ?>">Confirm</a>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container center_div register-form">
      <div class="col-sm-12">
      <div class="form-group">
              <label for="content">Comment (280 characters max):</label>
              <textarea name="comment" rows="5" onkeyup="displayTextareaLength(280);" id="comment-content" class="form-control" placeholder ="Your comment..."></textarea>
              <p id="textarea-counter"></p>
              <button class="btn btn-secondary" onclick="addComment('event',<?php echo $_GET['id'] ?>)" >Submit</button>
          </div>
      </div>
      <div id="comments">
      </div>
    </div>
</body>
    <?php
      unset($_SESSION["registredInEvent"]);
      unset($_SESSION["cancelledAttendance"]);
    ?>

<?php
  include "footer.php";
?>
