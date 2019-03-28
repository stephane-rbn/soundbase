<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (!isConnected()) {
      // Abort AJAX
      http_response_code(400);
      die();
  }

  // Connection to database
  $connection = connectDB();

  var_dump($_POST);

  if (count($_POST) != 2 || empty($_POST["playlist_id"]) || empty($_POST["track_id"])) {
      // Invalid form data
      http_response_code(400);
      die();
  } elseif (!is_numeric($_POST["playlist_id"]) || !is_numeric($_POST["track_id"])) {
      // Invalid form data
      http_response_code(400);
      die();
  }

  $removeInclusionQuery = $connection->query(
    "DELETE FROM inclusion WHERE playlist={$_POST["playlist_id"]} AND track={$_POST["track_id"]}"
  );

  $success = $removeInclusionQuery->execute();

  if (!$success) {
      // DELETE fail
      http_response_code(500);
      die();
  } else {
      http_response_code(201);
  }
