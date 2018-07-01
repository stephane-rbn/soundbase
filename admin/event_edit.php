<?php
  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../login.php");
  }

  $result = sqlSelect("SELECT * FROM events WHERE id='" . $_GET['id'] . "'");

  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <br>
        <?php
          if (isset($_SESSION["message"])) {
            fillAllFieldsErrorMessage();
          } else {
            successfulUpdateMessage();
          }
        ?>
        <div class="col-lg-12">
          <h1 class="page-header">Edit
            <?php
              echo $result['name'] .  " event";
            ?>
          </h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Edit event
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-6">
                  <form role="form" method="POST" action="script/updateEvent.php" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="image">Main image</label>
                      <input type="file" name="background_image"><?php
                        if (isErrorPresent(13)) {
                          echo '<p class="form_message_error">' . $listOfErrors[13] . '</p>';
                        } else if (isErrorPresent(14)) {
                          echo '<p class="form_message_error">' . $listOfErrors[14] . '</p>';
                        } else if (isErrorPresent(15)) {
                          echo '<p class="form_message_error">' . $listOfErrors[15] . '</p>';
                        } else {
                          echo "";
                        }
                      ?>
                    </div>
                    <div class="form-group">
                      <label>Name</label>
                      <input class="form-control" name="name" value="<?php echo fillSessionFieldSettings("name"); ?>" required="required">
                        <?php
                          if (isErrorPresent(1)) {
                            echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
                          }
                        ?>
                    </div>
                    <div class="form-group">
                      <label>Address</label>
                      <input class="form-control" name="address" value="<?php echo fillSessionFieldSettings("address"); ?>" required="required">
                        <?php
                          if (isErrorPresent(21)) {
                            echo '<p class="form_message_error">' . $listOfErrors[21] . '</p>';
                          }
                        ?>
                    </div>
                    <div class="form-group">
                      <label for="name">Capacity</label>
                      <input type="text" class="form-control" placeholder="20300" name="capacity" value="<?php echo fillSessionFieldSettings("capacity"); ?>" required="required"><?php
                        if (isErrorPresent(23)) {
                          echo '<p class="form_message_error">' . $listOfErrors[23] . '</p>';
                        }?>
                    </div>
                    <div class="form-group">
                      <label for="birthday">Date</label>
                      <input type="date" class="form-control" name="event_date" required="required" value="<?php echo fillSessionFieldSettings("event_date"); ?>">
                      <?php
                        if (isErrorPresent(3)) {
                          echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
                        } else if (isErrorPresent(4)) {
                          echo '<p class="form_message_error">' . $listOfErrors[4] . '</p>';
                        } else if (isErrorPresent(22)) {
                          echo '<p class="form_message_error">' . $listOfErrors[22] . '</p>';
                        } else {
                          echo '';
                        }
                      ?>
                    </div>
                    <div class="form-group">
                      <label for="description">Description (2500 characters)</label>
                      <textarea name="description" onkeyup="displayTextareaLength(2500);" id="textarea" class="form-control" placeholder ="Your description .."><?php echo fillSessionFieldSettings("description"); ?></textarea>
                      <p id="textarea-counter"></p>
                      <?php
                        if (isErrorPresent(12)) {
                          echo '<p class="form_message_error">' . $listOfErrors[12] . ' (2500)</p>';
                        }
                      ?>
                    </div>
                    <input type="hidden" name="event_id" value="<?php echo $_GET['id'];?>">
                    <button type="submit" class="btn btn-default">Submit</button>
                  </form>
                  <?php
                    unset($_SESSION["postForm"]);
                    unset($_SESSION["errorForm"]);
                    unset($_SESSION["message"]);
                    unset($_SESSION["successUpdate"]["eventInfo"]);
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php
  include "includes/footer.php";
?>
