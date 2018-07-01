<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (!isConnected()) {
    // Abort AJAX
    http_response_code(400);
    die();
  } else if (count($_POST) != 1 || empty($_POST["track"])) {
    // Invalid form data
    http_response_code(400);
    die();
  } else if (!is_numeric($_POST["track"])) {
    // Invalid form data
    http_response_code(400);
    die();
  }

  $connection = connectDB();

  // Get the number of likes
  $likesQuery = $connection->prepare(
    "SELECT COUNT(*) as likes FROM likes WHERE track=" . $_POST["track"]
  );
  $success = $likesQuery->execute();

  if(!$success) {
    // SELECT fail
    http_response_code(500);
    die();
  }

  $likes = $likesQuery->fetch(PDO::FETCH_ASSOC);
  $likesNumber = $likes['likes'];

  // From string to int
  $likesNumber = (int)$likesNumber;

  // Check if the track is currently liked by the user
  $isLikedQuery = $connection->prepare(
    "SELECT COUNT(*) as liked FROM likes WHERE track='" . $_POST["track"] . "' AND member='" . $_SESSION['id'] ."'"
  );
  $succes  = $isLikedQuery->execute();

  if(!$success) {
    // SELECT fail
    http_response_code(500);
    die();
  }

  $isLiked = $isLikedQuery->fetch(PDO::FETCH_ASSOC);
  $isLiked = $isLiked['liked'];

  if ($isLiked == 0) {
    // If the track is not liked, increment the like number and add a line in the table
    $likesNumber++;
    $query = $connection->prepare(
      "INSERT INTO likes (member,track) VALUES (" . $_SESSION['id'] ."," . $_POST["track"] . ")"
    );
    $success = $query->execute();

    if(!$success) {
      // SELECT fail
      http_response_code(500);
      die();
    }
  } else if ($isLiked == 1){
    // If the track is liked, decrement the like number and delete the line in the table
    $likesNumber--;
    $query = $connection->prepare(
      "DELETE FROM likes where member='" . $_SESSION['id'] ."' AND track='" . $_POST["track"] . "'"
    );
    $success = $query->execute();

    if(!$success) {
      // SELECT fail
      http_response_code(500);
      die();
    }
  }

  // Returns the like number so that we can update it with AJAX
  echo $likesNumber;
  http_response_code(201);
