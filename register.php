<?php
  session_start();

  require_once "functions.php";

  // redirect to home.php file if already connected
  if (isConnected()) {
      header("Location: home.php");
  }

  require_once "conf.inc.php";
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup">
      <h1>SIGN UP</h1>
      <h2>AS FAST AS SOUND</h2>
    </div>

    <div class="container">
    <?php
    if (isset($_SESSION["message"])) {
        fillAllFieldsErrorMessage();
    }
    ?>
    </div>

    <div class="container center_div register-form">


      <form method="POST" action="script/saveUser.php">

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="name">NAME</label>
              <input type="text" class="form-control" placeholder="Orelsan" name="name" value="<?php echo fillSessionField("name"); ?>" required="required">
              <?php
                if (isErrorPresent(1)) {
                    echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
                }
              ?>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="username">USERNAME</label>
              <input type="text" class="form-control" placeholder="orelsan20" name="username" value="<?php echo fillSessionField("username"); ?>" required="required">
              <?php
                if (isErrorPresent(2)) {
                    echo '<p class="form_message_error">' . $listOfErrors[2] . '</p>';
                } elseif (isErrorPresent(10)) {
                    echo '<p class="form_message_error">' . $listOfErrors[10] . '</p>';
                } else {
                    echo "";
                }
              ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="birthday">BIRTHDAY</label>
              <input type="date" class="form-control" placeholder="Date d'anniversaire" name="birthday" required="required" value="<?php echo fillSessionField("birthday"); ?>">
              <?php
                if (isErrorPresent(3)) {
                    echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
                } elseif (isErrorPresent(4)) {
                    echo '<p class="form_message_error">' . $listOfErrors[4] . '</p>';
                } elseif (isErrorPresent(5)) {
                    echo '<p class="form_message_error">' . $listOfErrors[5] . '</p>';
                } else {
                    echo '';
                }
              ?>
             </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="email">EMAIL</label>
              <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="<?php echo fillSessionField("email"); ?>" required="required">
              <?php
                if (isErrorPresent(6)) {
                    echo '<p class="form_message_error">' . $listOfErrors[6] . '</p>';
                } elseif (isErrorPresent(7)) {
                    echo '<p class="form_message_error">' . $listOfErrors[7] . '</p>';
                } else {
                    echo '';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="pwd">PASSWORD</label>
              <input type="password" class="form-control" name="pwd" required="required">
              <?php
                if (isErrorPresent(8)) {
                    echo '<p class="form_message_error">' . $listOfErrors[8] . '</p>';
                }
              ?>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="pwdConfirm">CONFIRMATION</label>
              <input type="password" class="form-control" name="pwdConfirm" required="required">
              <?php
                if (isErrorPresent(9)) {
                    echo '<p class="form_message_error">' . $listOfErrors[9] . '</p>';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="cgu" required="required">
          <label class="form-check-label" style="margin-bottom:10px;">I've read and accept the <a href="#">terms and conditions</a></label>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

        <p class="font-weight-light">
          <a href="login.php" style="font-size: 14px; text-decoration: none; color: #000;">( Already signed up? )</a>
        </p>

      </form>

    </div>

    <?php
      unset($_SESSION["message"]);
      unset($_SESSION["postForm"]);
      unset($_SESSION["errorForm"]);
    ?>

  <?php
    include "footer.php";
  ?>
