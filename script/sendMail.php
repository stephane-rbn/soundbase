<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if(isConnected()) {
    $userData = sqlSelect("SELECT name,username,email FROM member WHERE id=$_SESSION[id]");

    if (strlen($_POST["message"]) < 5 || strlen($_POST["message"]) > 1000) {
      $error = true;
      $listOfErrors[] = 1;
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../contact.php");
    }

    else {
      $message  = $_POST['message'];
      $name     = $userData['name'];
      $email    = $userData['email'];
    }
  } else {
    if (count($_POST) === 3
    && !empty($_POST["name"])
    && !empty($_POST["email"])
    && !empty($_POST["message"])
    ) {
      $_POST["name"]     = trim($_POST["name"]);
      $_POST["email"]    = strtolower(trim($_POST["email"]));
      $_POST["username"] = strtolower(trim($_POST["username"]));

      if (strlen($_POST["name"]) < 2 || strlen($_POST["name"]) > 60) {
        $error = true;
        $listOfErrors[] = 1;
      }

      if (strlen($_POST["message"]) < 5 || strlen($_POST["message"]) > 1000) {
        $error = true;
        $listOfErrors[] = 1;
      }

      if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $listOfErrors[] = 6;
      }

      if ($error) {
        $_SESSION["errorForm"] = $listOfErrors;
        $_SESSION["postForm"] = $_POST;
        header("Location: ../contact.php");
      }

      else {
        $name     = $_POST['name'];
        $message  = $_POST['message'];
        $email    = $_POST['mail'];
      }
    }
  }

  $to      = 'contact@soundbase.io'; // placeholder email address
  $subject = "Contact email from " . $name;
  $message = $message;
  $headers = 'From: "Soundbase" <noreply@soundbase.io>' . "\r\n" .  // Prevent email address spoofing
             'Reply-To: ' . $email . "\r\n";

  $_SESSION["sendMailSuccess"] = mail($to, $subject, $message, $headers);

  header("Location: ../contact.php");
?>
