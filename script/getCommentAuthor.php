<?php

  // The file returns JSON
  header('Content-Type: application/json');

  session_start();
  require_once "../functions.php";

  // Clean $_GET
  xssProtection();

  $connection = connectDB();

  if (count($_GET) != 1 || !isset($_GET['id'])) {
      // Invalid query strings
      http_response_code(400);
      die();
  }

  $query = $connection->prepare(
    "SELECT * FROM member WHERE id=". $_GET['id']
  );
  $success = $query->execute();

  if (!$success) {
      // SELECT fail
      http_response_code(500);
      die();
  }

  $result = $query->fetch(PDO::FETCH_ASSOC);

  // Return author info as JSON
  echo json_encode($result);
  http_response_code(200);
