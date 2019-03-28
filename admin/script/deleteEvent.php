<?php
  session_start();

  require "../../functions.php";

  if (!(isConnected() && isAdmin())) {
      header("Location: ../../login.php");
  }

  $connection = connectDB();

  // Query that delete a event with specific id
  $query = $connection->prepare(
    "DELETE FROM events WHERE id=:id"
  );

  // Execute the query
  $query->execute([
    "id" => $_GET["id"],
  ]);

  $_SESSION["sucessDeletion"] = true;

  header("Location: ../events.php");
