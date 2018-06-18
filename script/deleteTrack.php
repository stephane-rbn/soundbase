<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";

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

    $track = $_POST["track"];

    $connection = connectDB();

    // Check if the connected user owns the track
    $isOwnerQuery = $connection->prepare(
      "SELECT member FROM track WHERE id={$track}"
    );
    $isOwnerQuery->execute();
    $isOwnerResult = $isOwnerQuery->fetch(PDO::FETCH_ASSOC);

    // From array to string
    $isOwner = $isOwnerResult['member'];

    // If the user is the owner of the track, we can proceed to deletion
    if ($isOwner === $_SESSION['id']) {

      // Delete the likes on this track
      $deleteLikes = $connection->prepare(
        "DELETE FROM likes WHERE track={$track}"
      );

      // Delete the track from playlists
      $deleteFromPlaylists = $connection->prepare(
        "DELETE FROM inclusion WHERE track={$track}"
      );

      // Delete the listening history for this track
      $deleteListenings = $connection->prepare(
        "DELETE FROM listening WHERE track={$track}"
      );

      // Finally, delete the track
      $deleteTrack = $connection->prepare(
        "DELETE FROM track WHERE id={$track}"
      );

      // Execute the queries
      $deleteLikes->execute();
      $deleteFromPlaylists->execute();
      $deleteListenings->execute();
      $deleteTrack->execute();

      // Get cover and track file names
      $trackCoverQuery = $connection->prepare(
        "SELECT photo_filename FROM track WHERE id={$track}"
      );
      $trackFileQuery = $connection->prepare(
        "SELECT track_filename FROM track WHERE id={$track}"
      );

      $trackCoverQuery->execute();
      $trackFileQuery->execute();

      $trackCoverResult = $trackCoverQuery->fetch(PDO::FETCH_ASSOC);
      $trackFileResult = $trackFileQuery->fetch(PDO::FETCH_ASSOC);

      $trackCover = $trackCoverResult['photo_filename'];
      $trackFile = $trackFileResult['track_filename'];

      // Delete files
      unlink("../uploads/tracks/album_cover/" . $trackCover);
      unlink("../uploads/tracks/files/" . $trackFile);

    } else {
      // User is not owner of the track
      // Abort AJAX
      http_response_code(400);
    }
  } else {
      // Invalid request
      // Abort AJAX
      http_response_code(400);
  }
