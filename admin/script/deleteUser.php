<?php
  session_start();

  require "../../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../../login.php");
  }

  $connection = connectDB();

  // Query that delete a member with specific id
  $query = $connection->prepare("
    DELETE FROM events WHERE member=:id;
    DELETE FROM playlist WHERE member=:id;
    DELETE FROM track WHERE member=:id;
    DELETE FROM member WHERE id=:id
  ");

  // Execute the query
  $query->execute([
    "id" => $_GET["id"],
  ]);

  $_SESSION["sucessDeletion"] = true;

  header("Location: ../users.php");
