<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  $connection = connectDB();

  if (!isConnected()) {
      header("Location: login.php");
  } else {

      // Query that insert the new subscription
      $query = $connection->prepare("INSERT INTO subscription (member_following,member_followed) VALUES (:member_following,:member_followed)");

      $query->execute([
        "member_following" => $_SESSION["id"],
        "member_followed"  => $_GET["id"]
      ]);

      header("Location: ../profile.php?username=" . $_GET["username"]);
  }
