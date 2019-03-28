<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  if (!isConnected()) {
      http_response_code(400);
      die();
  }

  $connection = connectDB();

  // Query that deletes the subscription from the database
  $query = $connection->prepare("DELETE FROM subscription WHERE member_following= :member_following AND member_followed= :member_followed");

  // Execute the query
  $success = $query->execute([
    "member_following"=> $_SESSION["id"],
    "member_followed" => $_GET["id"]
  ]);

  if (!$succes) {
      // SELECT fail
      http_response_code(500);
      die();
  }

  header("Location: ../profile.php?username=" . $_GET["username"]);
