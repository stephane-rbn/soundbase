<?php

  session_start();
  require "functions.php";

  // redirect to home.php file if already connected
  if (isConnected()) {
    header("Location: home.php");
  }

  require_once "conf.inc.php";
  include "head.php";
  include "navbar.php";

?>

    <div class="wrapper" id="wrapper-login">
      <h1>CONNECTEZ-VOUS</h1>
      <h2>LA MUSIQUE VOUS ATTEND</h2>
    </div>

    <div class="push"></div>

    <div class="container-fluid"><?php loginErrorMessage(); ?></div>

    <div class="container center_div register-form">

      <form method="POST" action="script/userLogin.php">

        <div class="form-group">
          <label for="email">ADRESSE EMAIL</label>
          <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="" required="required">
        </div>

        <div class="form-group">
          <label for="pwd">MOT DE PASSE</label>
          <input type="password" class="form-control" name="pwd" required="required">
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

        <p class="font-weight-light">
          <a href="register.php" style="font-size: 14px; text-decoration: none; color: #000;">( Pas encore inscrit ? )</a>
        </p>

      </form>

    </div>

    <div class="push"></div>

<?php
  include "footer.php";
?>
