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
      <form method="POST" action="">

        <div class="form-group">
          <label for="name">NAME</label>
          <input type="text" class="form-control" placeholder="The Best Of 2018" name="name" value="<?php echo fillSessionField("name"); ?>" required="required"><?php
            if (isErrorPresent(19)) {
              echo '<p class="form_message_error">' . $listOfErrors[19] . '</p>';
            }?>
        </div>

      </form>
    </div>

<?php
  include "footer.php";
?>
