<?php
  session_start();

  require_once "../functions.php";

  // redirect to login page if not connected
  if (isConnected()) {

    // Connection to database
    $connection = connectDB();



    // Query that delete a member with specific id and token
    $query = $connection->prepare("
      DELETE FROM events WHERE member=:toto;
      DELETE FROM playlist WHERE member=:toto;
      DELETE FROM track WHERE member=:toto;
      DELETE FROM member WHERE id=:toto AND token=:tata
    ");

    // Execute the query
    $query->execute([
      "toto" => $_SESSION["id"],
      "tata" => $_SESSION["token"]
    ]);

    session_unset();
    session_destroy();
  }

  header("Location: ../index.php");
