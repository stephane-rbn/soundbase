<?php
  session_start();

  require_once "../functions.php";

  if (!isConnected()) {
    header("Location: login.php");
  } else {

    // Connection to database
    $connection = connectDB();

    // Query that gets a specific event based on the id passed
    $query = $connection->prepare(
      "SELECT * FROM events WHERE id={$_GET['id']}"
    );

    // Execute the query
    $query->execute();

    $event = $query->fetch(PDO::FETCH_ASSOC);
  }

  if ($_SESSION['id'] === $event['member']) {
    $query = $connection->prepare("DELETE FROM events WHERE id={$_GET["id"]}");

    // Execute the query
    $query->execute();

    header("Location: ../events.php");

  } else {
    echo "You do not have access !";
  }


