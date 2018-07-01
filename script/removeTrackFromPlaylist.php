<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  // Connection to database
  $connection = connectDB();

  $playlistQuery = $connection->query(
    "SELECT id FROM playlist WHERE id={$_POST["playlist_id"]}"
  );

  $playlist = $playlistQuery->fetch(PDO::FETCH_ASSOC);

  if (count($_POST) === 2
    && $_POST["playlist_id"]
    && $_POST["track_id"]
  ) {

    $removeInclusionQuery = $connection->query(
      "DELETE FROM inclusion WHERE playlist={$_POST["playlist_id"]} AND track={$_POST["track_id"]}"
    );

    $res = $removeInclusionQuery->execute();

    if ($res) {
      http_response_code(200);
    } else {
      http_response_code(500);
    }

  } else {
  http_response_code(400);
}
