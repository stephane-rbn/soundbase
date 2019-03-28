<?php
  session_start();

  require_once "functions.php";
  $navbarItem = 'contact';
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup">
      <h1>CONTACT US</h1>
      <h2>NEED HELP?</h2>
    </div>

    <div class="container center_div register-form">

    <?php
      if (isset($_SESSION["sendMailSuccess"])) {
          if ($_SESSION["sendMailSuccess"] === true) {
              echo '<div class="alert alert-success">';
              echo '<strong>Success!</strong> Your email has been sent.';
          } elseif ($_SESSION["sendMailSuccess"] === false) {
              echo '<div class="alert alert-danger">';
              echo "<strong>Oops!</strong> Something went wrong and we couldn't send your email.";
          }
          echo '</div>';
      }
    ?>
      <form method="POST" action="script/sendMail.php">

          <?php if (!isConnected()) {
        ?>
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
          <?php
    } ?>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="content">MESSAGE</label>
              <br>
              <textarea name="message" cols="50" rows="10"></textarea>
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
      unset($_SESSION["sendMailSuccess"]); // Display the success message one time only
    ?>

  <?php
    include "footer.php";
  ?>
