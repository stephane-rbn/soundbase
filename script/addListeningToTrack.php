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

    if (isConnected()) {
      $member = $_SESSION['id'];
    } else {
      // If the listening is from a visitor, use 0 as the member ID.
      $member = 0;
    }
    $query = $connection->prepare(
      "INSERT INTO listening (member, track, listening_date) VALUES (?,?,?)"
    );
    $query->execute([
      $member,
      $_POST["track"],
      date("Y-m-d H:i:s")
    ]);

    // Get the number of listenings
    $listeningsQuery = $connection->prepare(
      "SELECT COUNT(*) as listenings FROM listening WHERE track=" . $_POST["track"]
    );
    $listeningsQuery->execute();
    $listenings = $listeningsQuery->fetch(PDO::FETCH_ASSOC);
    $listeningsNumber = $listenings['listenings'];

    // From string to int
    $listeningsNumber = (int)$listeningsNumber;

    // Returns the listenings number so that we can update it with AJAX
    echo $listeningsNumber;

  } else {
    // Abort AJAX
    http_response_code(400);
  }
