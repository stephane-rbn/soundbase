<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (!isConnected()) {
    // Abort AJAX
    http_response_code(400);
  }

  if (count($_POST) === 1 && !empty($_POST["track"])) {

    if (!is_numeric($_POST["track"])) {
      // Abort AJAX
      http_response_code(400);
    }
    $connection = connectDB();

    // Get the number of likes
    $likesQuery = $connection->prepare(
      "SELECT COUNT(*) as likes FROM likes WHERE track=" . $_POST["track"]
    );
    $likesQuery->execute();
    $likes = $likesQuery->fetch(PDO::FETCH_ASSOC);
    $likesNumber = $likes['likes'];

    // From string to int
    $likesNumber = (int)$likesNumber;

    // Check if the track is currently liked by the user
    $isLikedQuery = $connection->prepare(
      "SELECT COUNT(*) as liked FROM likes WHERE track='" . $_POST["track"] . "' AND member='" . $_SESSION['id'] ."'"
    );
    $isLikedQuery->execute();
    $isLiked = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
    $isLiked = $isLiked['liked'];

    if ($isLiked == 0) {
      // If the track is not liked, increment the like number and add a line in the table
      $likesNumber++;
      $query = $connection->prepare(
        "INSERT INTO likes (member,track) VALUES (" . $_SESSION['id'] ."," . $_POST["track"] . ")"
      );
      $query->execute();
    }
    else if ($isLiked == 1){
      // If the track is liked, decrement the like number and delete the line in the table
      $likesNumber--;
      $query = $connection->prepare(
        "DELETE FROM likes where member='" . $_SESSION['id'] ."' AND track='" . $_POST["track"] . "'"
      );
      $query->execute();
    }

    // Returns the like number so that we can update it with AJAX
    echo $likesNumber;

  } else {
    // Abort AJAX
    http_response_code(400);
  }
