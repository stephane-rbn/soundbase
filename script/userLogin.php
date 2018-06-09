<?php
  session_start();
  require_once "../conf.inc.php";
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 2 && !empty($_POST["email"] && !empty($_POST["pwd"]))) {

    $_POST["email"] = strtolower($_POST["email"]);

    $data = sqlSelect("SELECT * FROM member WHERE email='{$_POST['email']}'");

    if (password_verify($_POST["pwd"], $data["password"])) {
      $_SESSION["auth"]  = true;
      $_SESSION["id"]    = $data["id"];
      $_SESSION["token"] = $data["token"];

      header("Location: ../home.php");
    } else {
      $_SESSION["message"] = "Erreur : l'email ou le mot de passe ne correspond pas";
      header("Location: ../login.php");
    }

  } else {
    $_SESSION["message"] = true;
    header("Location: ../login.php");
  }
?>
