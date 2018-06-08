<?php

  session_start();
  require_once "conf.inc.php";
  require_once "functions.php";

  xssProtection();

  if (count($_GET) === 2 && !empty($_GET["username"] && !empty($_GET["token"]))) {

    $username = $_GET['username'];
    $token = $_GET['token'];

    $result = sqlSelect("SELECT confirmation FROM member WHERE username='" . $username . "'");

    // If token is correct, confirm user
    if ($result["confirmation"] === $token) {

      $connection = connectDB();

      // Replace confirmation token with "1"
      $updateQuery = $connection->prepare(
        "UPDATE member
        SET confirmation='1'
        WHERE username='" . $username . "'"
      );

      $updateQuery->execute();

      $_SESSION["accountConfirmed"] = true;
      header("Location: ../login.php");

    } // If user is already confirmed, display message
    else if ($result["confirmation"] === "1") {

      $_SESSION["accountConfirmed"] = "alreadyConfirmed";
      header("Location: ../login.php");

    } // Token is not correct
    else {

      $_SESSION["accountConfirmed"] = false;
      header("Location: ../login.php");

    }

  }
  // GET parameters are not correct
  else {

    $_SESSION["accountConfirmed"] = false;
    header("Location: ../login.php");

  }

?>
