<?php

session_start();
require_once "conf.inc.php";
require_once "functions.php";

// redirect to home.php file if already connected
if (isConnected()) {
  header("Location: home.php");
}

include "head.php";
include "navbar.php";

if (count($_POST) === 2 && !empty($_POST["email"] && !empty($_POST["pwd"]))) {

  $_POST["email"] = strtolower($_POST["email"]);

  // Connection to database
  $connection = connectDB();

  // Query that get the password that matching with the email given
  $query = $connection->prepare("SELECT * FROM MEMBER WHERE email=:toto");

  // Execute the query
  $query->execute([
    "toto" => $_POST["email"]
  ]);

  // Fetch data with the query
  $data = $query->fetch();

  if (password_verify($_POST["pwd"], $data["password"])) {
    $_SESSION["auth"] = true;
    $_SESSION["id"] = $data["id"];
    header("Location: home.php");
  } else {
    echo "NOK";
  }

}

?>

    <div class="wrapper" id="wrapper-login">
      <h1>CONNECTEZ-VOUS</h1>
      <h2>LA MUSIQUE VOUS ATTEND</h2>
    </div>

    <div class="push"></div>

    <div class="container center_div register-form">

      <form method="POST">

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

      </form>

    </div>

    <div class="push"></div>

<?php
  include "footer.php";
?>
