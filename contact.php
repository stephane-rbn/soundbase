<?php
  session_start();

  require_once "functions.php";

  require_once "conf.inc.php";
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup">
      <h1>CONTACTEZ NOUS</h1>
      <h2>A LA VITESSE DU SON</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/saveUser.php">

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <label for="name">NOM</label>
              <input type="text" class="form-control" placeholder="Orelsan" name="name" value="<?php echo fillSessionField("name"); ?>" required="required">
              <?php
                if (isErrorPresent(1)) {
                  echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
                }
              ?>
            </div>
          </div>

          <div class="col-sm-12">
            <div class="form-group">
              <label for="email">ADRESSE EMAIL</label>
              <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="<?php echo fillSessionField("email"); ?>" required="required">
              <?php
                if (isErrorPresent(2)) {
                  echo '<p class="form_message_error">' . $listOfErrors[2] . '</p>';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <label for="content">EMAIL</label>
            <br>
            <textarea cols="50" rows="10"></textarea>
            <?php
              if (isErrorPresent(3)) {
                echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
              }
            ?>
          </div>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="cgu" required="required">
          <label class="form-check-label" style="margin-bottom:10px;">J'accepte les CGUs de ce site</label>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

        <p class="font-weight-light">
          <a href="login.php" style="font-size: 14px; text-decoration: none; color: #000;">( Déjà inscrit ? )</a>
        </p>

      </form>

    </div>

    <?php
      unset($_SESSION["postForm"]);
      unset($_SESSION["errorForm"]);
    ?>

  <?php
    include "footer.php";
  ?>
