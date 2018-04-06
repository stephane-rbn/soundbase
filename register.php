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
      <h1>INSCRIVEZ-VOUS</h1>
      <h2>A LA VITESSE DU SON</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/saveUser.php">

        <?php
          function fillSessionField($field) {
            return isset($_SESSION["postForm"]) ? $_SESSION["postForm"][$field] : "";
          }

          function isErrorPresent($errorNumber) {
            if (isset($_SESSION["errorForm"])) {
              for ($i = 0; $i < count($_SESSION["errorForm"]); $i++) {
                foreach ($_SESSION["errorForm"] as $key) {
                  if ($errorNumber === $key) {
                    return true;
                  }
                }
              }
            }

            return false;
          }
        ?>

        <div class="form-group">
          <label for="firstName">PRÉNOM</label>
          <input type="text" class="form-control" placeholder="Aurélien" name="firstName" value="<?php echo fillSessionField("firstName"); ?>" required="required">
          <?php
            if (isErrorPresent(1)) {
              echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="lastName">NOM</label>
          <input type="text" class="form-control" placeholder="Cotentin" name="lastName" value="<?php echo fillSessionField("lastName"); ?>" required="required">
          <?php
            if (isErrorPresent(2)) {
              echo '<p class="form_message_error">' . $listOfErrors[2] . '</p>';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="birthday">DATE DE NAISSANCE</label>
          <input type="date" class="form-control" placeholder="Date d'anniversaire" name="birthday" required="required" value="<?php echo fillSessionField("birthday"); ?>">
          <?php
            if (isErrorPresent(6)) {
              echo '<p class="form_message_error">' . $listOfErrors[6] . '</p>';
            } else if (isErrorPresent(7)) {
              echo '<p class="form_message_error">' . $listOfErrors[7] . '</p>';
            } else if (isErrorPresent(8)) {
              echo '<p class="form_message_error">' . $listOfErrors[8] . '</p>';
            } else {
              echo '';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="musicianName">NOM D'ARTISTE</label>
          <input type="text" class="form-control" placeholder="Orelsan" name="musicianName" value="<?php echo fillSessionField("musicianName"); ?>" required="required">
          <?php
            if (isErrorPresent(3)) {
              echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="email">ADRESSE EMAIL</label>
          <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="<?php echo fillSessionField("email"); ?>" required="required">
          <?php
            if (isErrorPresent(4)) {
              echo '<p class="form_message_error">' . $listOfErrors[4] . '</p>';
            } else if (isErrorPresent(5)) {
              echo '<p class="form_message_error">' . $listOfErrors[5] . '</p>';
            } else {
              echo '';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="pwd">MOT DE PASSE</label>
          <input type="password" class="form-control" name="pwd" required="required">
          <?php
            if (isErrorPresent(9)) {
              echo '<p class="form_message_error">' . $listOfErrors[9] . '</p>';
            }
          ?>
        </div>

        <div class="form-group">
          <label for="pwdConfirm">CONFIRMATION</label>
          <input type="password" class="form-control" name="pwdConfirm" required="required">
          <?php
            if (isErrorPresent(10)) {
              echo '<p class="form_message_error">' . $listOfErrors[10] . '</p>';
            }
          ?>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="cgu" required="required">
          <label class="form-check-label" style="margin-bottom:10px;">J'accepte les CGUs de ce site</label>
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

      </div>

  <?php
    include "footer.php";
  ?>
