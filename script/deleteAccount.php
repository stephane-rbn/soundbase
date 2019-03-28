<?php
  session_start();

  require_once "../functions.php";

  xssProtection();

  // redirect to login page if not connected
  if (isConnected()) {

    // Connection to database
      $connection = connectDB();

      // Query that delete a member with specific id and token
      $query = $connection->prepare("
      DELETE FROM events WHERE member=:id;
      DELETE FROM playlist WHERE member=:id;
      DELETE FROM track WHERE member=:id;
      DELETE FROM member WHERE id=:id AND token=:token
    ");

      // Execute the query
      $query->execute([
      "id" => $_SESSION["id"],
      "token" => $_SESSION["token"]
    ]);

      session_unset();
      session_destroy();
  }

  header("Location: ../index.php");
