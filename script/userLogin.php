<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 2 && !empty($_POST["email"] && !empty($_POST["pwd"]))) {

    $_POST["email"] = strtolower($_POST["email"]);

    $data = sqlSelect("SELECT * FROM member WHERE email='{$_POST['email']}'");

    if (password_verify($_POST["pwd"], $data["password"])) {
      if ($data["confirmation"] === "1") {
        $_SESSION["auth"]  = true;
        $_SESSION["id"]    = $data["id"];
        $_SESSION["token"] = $data["token"];

        header("Location: ../home.php");
      } else {
        $_SESSION["failedLogin"] = "Error: Your account has not been confirmed. Check your emails!";
        header("Location: ../login.php");
      }
    } else {
      $_SESSION["failedLogin"] = "Error: Your username or password is wrong.";
      header("Location: ../login.php");
    }

  } else {
    $_SESSION["failedLogin"] = "Error: Please fill all the fields";
    header("Location: ../login.php");
  }
?>
