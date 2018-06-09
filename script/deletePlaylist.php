<?php
  session_start();

  require "../functions.php";

  $connection = connectDB();

  $query = $connection->prepare("DELETE FROM playlist WHERE id={$_GET["id"]}");

    // Execute the query
    $query->execute();

  header("Location: ../myPlaylists.php");
