<?php
  session_start();

  require_once "functions.php";

  // redirect to login.php file if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  require_once "conf.inc.php";
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup" style="background-image: url('vendor/images/events/background.jpg');">
      <h1>NEW EVENT</h1>
      <h2>INVITE AND MEET YOUR FANS AT YOUR OWN CONCERT</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/saveEvent.php" enctype="multipart/form-data">

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="name">NAME</label>
            <input type="text" class="form-control" placeholder="National Day Concert #210" name="name" value="<?php echo fillSessionField("name"); ?>" required="required"><?php
              if (isErrorPresent(1)) {
                echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
              }?>
          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <label for="birthday">DATE</label>
            <input type="date" class="form-control" name="event_date" required="required" value="<?php echo fillSessionField("event_date"); ?>">
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
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group">
            <label for="name">ADDRESS <span style="font-size: 13px;">(optional)</span></label>
            <input type="text" class="form-control" placeholder="AccorHotels Arena - 8 Boulevard de Bercy, 75012 Paris" name="address" value="<?php echo fillSessionField("address"); ?>"><?php
              if (isErrorPresent(21)) {
                echo '<p class="form_message_error">' . $listOfErrors[21] . '</p>';
              } ?>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <div class="row">
              <label class="col-sm-12">MAIN IMAGE</label>
            </div>
            <div class="row">
              <input class="col-sm-12" type="file" name="image" required/><?php
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
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="name">CAPACITY</label>
            <input type="text" class="form-control" placeholder="20300" name="capacity" value="<?php echo fillSessionField("capacity"); ?>" required="required"><?php
              if (isErrorPresent(23)) {
                echo '<p class="form_message_error">' . $listOfErrors[23] . '</p>';
              }?>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-12">DESCRIPTION (2500 characters)</label>
        <textarea name="description" onkeyup="displayStrLength(2500);" id="textarea" class="form-control" rows="10" placeholder ="Your description .."><?php echo fillSessionField("description"); ?></textarea>
        <p id="count"></p>
        <?php
          if (isErrorPresent(12)) {
            echo '<p class="form_message_error">' . $listOfErrors[12] . ' (2500)</p>';
          }
        ?>
      </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

      </form>

      <?php
        unset($_SESSION["postForm"]);
        unset($_SESSION["errorForm"]);
        unset($_SESSION["successUpdate"]);
      ?>

    </div>

<?php
  include "footer.php";
?>
