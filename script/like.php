<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if (count($_POST) === 1 && !empty($_POST["track"])) {

    if (!is_numeric($_POST["track"])) {
      // Abort AJAX
      http_response_code(400);
    }

    $connection = connectDB();

    // Get the current number of likes
    $query = $connection->prepare(
      "SELECT likes FROM track where id=" . $_POST["track"] . ""
    );
    $query->execute();
    $likesNumber = $query->fetch(PDO::FETCH_ASSOC);

    // From array to string
    $likesNumber = $likesNumber["likes"];

    // From string to int
    $likesNumber = (int)$likesNumber;

    // Add one like
    $likesNumber++;

    // Update the DB with the new number of likes
    $query = $connection->prepare(
      "UPDATE track set likes = " .$likesNumber . " WHERE id =" . $_POST["track"] . ""
    );
    $query->execute();

  } else {
    // Abort AJAX
    http_response_code(400);
  }
