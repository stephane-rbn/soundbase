<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  $userData = sqlSelect("SELECT name,username,email FROM member WHERE id=$_SESSION[id]");

  $to      = 'contact@soundbase.io'; // placeholder email address
  $subject = "Contact email from " . $userData['name'] . " (" . $userData['username'] .")";
  $message = $_POST['message'];
  $headers = 'From: "Soundbase" <noreply@soundbase.io>' . "\r\n" .  // Prevent email address spoofing
             'Reply-To: ' . $userData['email'] . "\r\n";

  $_SESSION["sendMailSuccess"] = mail($to, $subject, $message, $headers);

  header("Location: ../contact.php");
?>
