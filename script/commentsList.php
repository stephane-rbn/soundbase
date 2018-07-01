<?php

  // The file returns JSON
  header('Content-Type: application/json');

  session_start();
  require_once "../functions.php";

  // Clean $_GET
  xssProtection();


  if(count($_GET) != 2 || empty($_GET['contentType']) || empty($_GET['contentId'])) {
    // Invalid query strings
    http_response_code(400);
    die();
  }

  $connection = connectDB();

  $query = $connection->prepare(
    "SELECT * FROM comment
    WHERE " . $_GET['contentType'] . "=". $_GET['contentId'] . "
    ORDER BY publication_date DESC"
  );

  $succes = $query->execute();
  $comments = $query->fetchAll(PDO::FETCH_ASSOC);

  if(!$succes) {
    // SELECT fail
    http_response_code(500);
    die();
  }

    // Output the comments to JSON
    echo json_encode($comments);
    http_response_code(200);
