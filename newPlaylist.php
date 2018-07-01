<?php
  session_start();

  require_once "functions.php";

  // redirect to login.php file if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  require_once "conf.inc.php";
  $navbarItem = 'new';
  require "head.php";
  include "navbar.php";
?>


    <div class="wrapper" id="wrapper-signup" style="background-image: url('vendor/images/newplaylistpage/background.jpg');">
      <h1>NEW PLAYLIST</h1>
      <h2>FIND YOUR FAVORITE TRACKS EVERYWHERE</h2>
    </div>

    <div class="container center_div register-form">
      <form method="POST" action="script/savePlaylist.php">

        <div class="form-group">
          <label for="name">NAME</label>
          <input type="text" class="form-control" placeholder="ex: The Best Of 2017" name="name" value="<?php echo fillSessionField("name"); ?>" required="required"><?php
            if (isErrorPresent(19)) {
              echo '<p class="form_message_error">' . $listOfErrors[19] . '</p>';
            }?>
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
