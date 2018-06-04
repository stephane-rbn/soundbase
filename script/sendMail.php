<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  $userData = sqlSelect("SELECT name,username,email FROM member WHERE id=$_SESSION[id]");

  $to      = 'contact@soundbase.io'; // placeholder email address
  $subject = "Contact email from " . $userData[0]['name'] . " (" . $userData[0]['username'] .")";
  $message = $_POST['message'];
  $headers = 'From: "Soundbase" <noreply@soundbase.io>' . "\r\n" .  // Prevent email address spoofing
             'Reply-To: ' . $userData[0]['email'] . "\r\n";

  mail($to, $subject, $message, $headers);

  $_SESSION["sendMailSuccess"] = true;

  header("Location: ../contact.php");
?>
