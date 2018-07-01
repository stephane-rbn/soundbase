<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  // Connection to database
  $connection = connectDB();

  $playlistQuery = $connection->query(
    "SELECT * FROM playlist WHERE id={$_GET["playlist_id"]}"
  );

  $playlist = $playlistQuery->fetch(PDO::FETCH_ASSOC);

  if (count($_GET) === 2
  && !empty($_GET["playlist_id"])
  && !empty($_GET["track_id"])
  ) {

    if ($_SESSION["id"] !== $playlist["member"]) {
      die("Error: You can't add tracks in a playlist that is not yours");
    } else {

      $checkExistanceQuery = $connection->prepare("SELECT 1 FROM inclusion WHERE playlist='{$_GET['playlist_id']}' AND track='{$_GET['track_id']}'");

      $checkExistanceQuery->execute();

      $result = $checkExistanceQuery->fetch();

      if (!empty($result)) {
        die("Error: You have already added this track in this playlist");
      }

      // Query that add a specific track to a specific playlist
      $query = $connection->prepare(
        "INSERT INTO inclusion (playlist, track) VALUES ('{$_GET['playlist_id']}','{$_GET['track_id']}')"
      );

      $query->execute();

      header("Location: ../playlist.php?id=" . $_GET["playlist_id"]);
    }
  } else {
    die("Error: please fill all fields");
  }
