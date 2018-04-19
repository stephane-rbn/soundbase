<?php
  session_start();

  require "../functions.php";

  // redirect to login page if not connected
  if (isConnected()) {

    // Connection to database
    $connection = connectDB();

    // Query that delete a member with specific id and token
    $query = $connection->prepare("DELETE FROM MEMBER WHERE id=:toto AND token=:tata");

    // Execute the query
    $query->execute([
      "toto" => $_SESSION["id"],
      "tata" => $_SESSION["token"]
    ]);

    session_unset();
    session_destroy();
  }

  header("Location: ../index.php");

