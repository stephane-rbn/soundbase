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

    <div class="wrapper" id="wrapper-signup" style="background-image: url('vendor/images/newtrackpage/background.jpg');">
      <h1>NEW TRACK</h1>
      <h2>SHARE YOUR CREATION RIGHT NOW</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/saveTrack.php" enctype="multipart/form-data">

        <div class="form-group">
          <label for="title">TITLE</label>
          <input type="text" class="form-control" placeholder="La pluie (feat. Stromae)" name="title" value="<?php echo fillSessionField("title"); ?>" required="required"><?php
            if (isErrorPresent(19)) {
              echo '<p class="form_message_error">' . $listOfErrors[19] . '</p>';
            }?>
        </div>

        <div class="form-group">
          <label for="genre">GENRE</label>
          <select class="form-control" name="genre" required="required">
            <?php
              foreach ($listOfGenres as $key => $value) {
            ?>
            <option value="<?php echo $key; ?>"
            <?php
              if (isset($_SESSION["postForm"]) && $_SESSION["postForm"]["genre"]) {
                echo 'selected="selected"';
              } else if (!isset($_SESSION["postForm"]) && $defaultGenre === $key) {
                echo 'selected="selected"';
              }
            ?>
            ><?php echo $value; ?></option>
            <?php } ?>
          </select>
          <?php
            if (isErrorPresent(20)) {
              echo '<p class="form_message_error">' . $listOfErrors[20] . '</p>';
            }?>
        </div>

        <div class="form-group">
          <div class="row">
            <label class="col-sm-12">TRACK FILE</label>
          </div>
          <div class="row">
            <input class="col-sm-12" type="file" name="track" required/><?php
              if (isErrorPresent(16)) {
                echo '<p class="form_message_error">' . $listOfErrors[16] . '</p>';
              } else if (isErrorPresent(17)) {
                echo '<p class="form_message_error">' . $listOfErrors[17] . '</p>';
              } else if (isErrorPresent(18)) {
                echo '<p class="form_message_error">' . $listOfErrors[18] . '</p>';
              } else {
                echo "";
              }
            ?>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <label class="col-sm-12">TRACK COVER</label>
          </div>
          <div class="row">
            <input class="col-sm-12" type="file" name="cover" required/><?php
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

        <div class="form-group">
          <label class="col-sm-12">DESCRIPTION (200 characters)</label>
          <textarea name="description" onkeyup="displayTextareaLength(200);" id="textarea" class="form-control" rows="10" placeholder ="Your description .."><?php echo fillSessionField("description"); ?></textarea>
          <p id="textarea-counter"></p>
          <?php
            if (isErrorPresent(12)) {
            echo '<p class="form_message_error">' . $listOfErrors[12] . ' (200)</p>';
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
