<?php

  // The file returns JSON
  header('Content-Type: application/json');

  session_start();
  require "../functions.php";

  // Clean $_GET
  xssProtection();

  $connection = connectDB();

  // Get the comments associated to the content

  if(count($_GET) === 2 &&
    !empty($_GET['contentType']) &&
    !empty($_GET['contentId'])) {

      $query = $connection->prepare(
        "SELECT * FROM comment
        WHERE " . $_GET['contentType'] . "=". $_GET['contentId'] . "
        ORDER BY publication_date DESC"
      );

    $succes = $query->execute();
    $comments = $query->fetchAll(PDO::FETCH_ASSOC);

    if($succes) {
      // SELECT success
      // Output the comments to JSON
      echo json_encode($comments);
      http_response_code(200);
    } else {
      // SELECT fail
      http_response_code(500);
    }
  } else {
    // Invalid query strings
    http_response_code(400);
  }

?>
