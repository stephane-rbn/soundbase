<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) != 1 || empty($_POST["track"])) {
    // Invalid form data
    http_response_code(400);
    die();
  } else if (!is_numeric($_POST["track"])) {
    // Invalid form data
    http_response_code(400);
    die();
  }

  $connection = connectDB();

  if (isConnected()) {
    $member = $_SESSION['id'];
  } else {
    // If the listening is from a visitor, use 0 as the member ID.
    $member = 0;
  }
  $query = $connection->prepare(
    "INSERT INTO listening (member, track, listening_date) VALUES (?,?,?)"
  );
  $success = $query->execute([
    $member,
    $_POST["track"],
    date("Y-m-d H:i:s")
  ]);

  if(!$success) {
    // INSERT fail
    http_response_code(500);
    die();
  }

  // Get the number of listenings
  $listeningsQuery = $connection->prepare(
    "SELECT COUNT(*) as listenings FROM listening WHERE track=" . $_POST["track"]
  );
  $success = $listeningsQuery->execute();

  if(!$success) {
    // SELECT fail
    http_response_code(500);
    die();
  }

  $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);
  $listeningsNumber = $listenings['listenings'];

  // Returns the listenings number so that we can update it with AJAX
  echo $listeningsNumber;
  http_response_code(201);
