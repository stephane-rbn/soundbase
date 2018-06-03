<?php
  session_start();

  require_once "functions.php";

  require_once "conf.inc.php";
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup">
      <h1>CONTACT US</h1>
      <h2>NEED HELP?</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/sendMail.php">

        <?php if (!isConnected()) {?>
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">NAME</label>
              <input type="text" class="form-control" placeholder="Orelsan" name="name" value="<?php echo fillSessionField("name"); ?>" required="required">
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="email">EMAIL ADDRESS</label>
              <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="<?php echo fillSessionField("email"); ?>" required="required">
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="col-sm-12">
          <div class="form-group">
            <label for="content">MESSAGE</label>
            <br>
            <textarea cols="50" rows="10"></textarea>
          </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

      </form>

    </div>

    <?php
      unset($_SESSION["postForm"]);
      unset($_SESSION["errorForm"]);
    ?>

  <?php
    include "footer.php";
  ?>
