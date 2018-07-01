<?php

  session_start();
  require_once "functions.php";

  // redirect to home.php file if already connected
  if (isConnected()) {
    header("Location: home.php");
  }

  include "head.php";
  include "navbar.php";

?>

    <div class="wrapper" id="wrapper-login">
      <h1>LOG IN</h1>
      <h2>MUSIC IS WAITING FOR YOU</h2>
    </div>

    <?php
      if (isset($_SESSION["accountConfirmed"])) {

        echo '<div class="push"></div>';
        echo '<div class="container-fluid">';

        if ($_SESSION["accountConfirmed"] === true) {
          echo '<div class="alert alert-success">';
            echo '<strong>Success!</strong> Your account has been verified. You can now log in.';
        }
        else if ($_SESSION["accountConfirmed"] === false) {
          echo '<div class="alert alert-danger">';
            echo "<strong>Oops!</strong> Your account couldn't be verified...";
        }
        else if ($_SESSION["accountConfirmed"] === "alreadyConfirmed") {
          echo '<div class="alert alert-info">';
            echo "Your account has already been confirmed.";
        }

        echo '</div>';
        echo '</div>';

      } else if (isset($_SESSION["failedLogin"])) {
        echo '<div class="push"></div>';
        echo '<div class="container-fluid">';
          echo '<div class="alert alert-danger">';
            echo $_SESSION["failedLogin"];
          echo '</div>';
        echo '</div>';
      }

      unset($_SESSION["accountConfirmed"]);
      unset($_SESSION["failedLogin"]);
    ?>

    <div class="container center_div register-form">

      <form method="POST" action="script/userLogin.php">

        <div class="form-group">
          <label for="email">EMAIL</label>
          <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="" required="required">
        </div>

        <div class="form-group">
          <label for="pwd">PASSWORD</label>
          <input type="password" class="form-control" name="pwd" required="required">
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

        <p class="font-weight-light">
          <a href="register.php" style="font-size: 14px; text-decoration: none; color: #000;">( Don’t have an account? )</a>
        </p>

      </form>

    </div>

    <div class="push"></div>

<?php
  include "footer.php";
?>
