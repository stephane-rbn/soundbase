<?php

session_start();
require_once "conf.inc.php";
require_once "functions.php";

// redirect to home.php file if connected
if (isConnected()) {
  header("Location: home.php");
}

include "head.php";
include "navbar.php";

$_POST["email"] = strtolower($_POST["email"]);

if (count($_POST) === 2 && isset($_POST["email"]) && isset($_POST["pwd"])) {

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

    <section>
      <form method="POST">
        <input type="text" name="email" placeholder="Votre email">
        <input type="password" name="pwd" placeholder="Votre mot de passe">
        <input type="submit" value="Se connecter">
      </form>
    </section>

<?php
  include "footer.php";
?>
