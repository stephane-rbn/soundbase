<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (!isConnected()) {
      // Abort AJAX
      http_response_code(400);
      die();
  }

  if (count($_POST) != 1 || empty($_POST["track"])) {
      // Invalid form data
      http_response_code(400);
      die();
  } elseif (!is_numeric($_POST["track"])) {
      // Invalid form data
      http_response_code(400);
      die();
  }

    $track = $_POST["track"];

    $connection = connectDB();

    // Check if the connected user owns the track
    $trackOwnerQuery = $connection->prepare(
      "SELECT member FROM track WHERE id={$track}"
    );
    $success = $trackOwnerQuery->execute();

    if (!$success) {
        // SELECT fail
        http_response_code(500);
        die();
    }

    $trackOwnerResult = $trackOwnerQuery->fetch(PDO::FETCH_ASSOC);

    // From array to string
    $trackOwner = $trackOwnerResult['member'];

    // If the user is the owner of the track, we can proceed to deletion
    if ($trackOwner != $_SESSION['id']) {
        // Abort AJAX
        http_response_code(400);
        die();
    }

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
    $success = $deleteLikes->execute();
    if (!$success) {
        // DELETE fail
        http_response_code(500);
        die();
    }
    $success = $deleteFromPlaylists->execute();
    if (!$success) {
        // DELETE fail
        http_response_code(500);
        die();
    }
    $success = $deleteListenings->execute();
    if (!$success) {
        // DELETE fail
        http_response_code(500);
        die();
    }
    $success = $deleteTrack->execute();
    if (!$success) {
        // DELETE fail
        http_response_code(500);
        die();
    }

    // Get cover and track file names
    $trackCoverQuery = $connection->prepare(
      "SELECT photo_filename FROM track WHERE id={$track}"
    );
    $trackFileQuery = $connection->prepare(
      "SELECT track_filename FROM track WHERE id={$track}"
    );

    $success = $trackCoverQuery->execute();
    if (!$success) {
        // SELECT fail
        http_response_code(500);
        die();
    }
    $success = $trackFileQuery->execute();
    if (!$success) {
        // SELECT fail
        http_response_code(500);
        die();
    }

    $trackCoverResult = $trackCoverQuery->fetch(PDO::FETCH_ASSOC);
    $trackFileResult = $trackFileQuery->fetch(PDO::FETCH_ASSOC);

    $trackCover = $trackCoverResult['photo_filename'];
    $trackFile = $trackFileResult['track_filename'];

    // Delete files
    unlink("../uploads/tracks/album_cover/" . $trackCover);
    unlink("../uploads/tracks/files/" . $trackFile);

    http_response_code(201);
