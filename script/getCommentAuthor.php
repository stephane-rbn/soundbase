<?php

  // The file returns JSON
  header('Content-Type: application/json');

  session_start();
  require "../functions.php";

  // Clean $_GET
  xssProtection();

  $connection = connectDB();

  if (count($_GET) === 1 && isset($_GET['id'])){

    $query = $connection->prepare (
      "SELECT * FROM member WHERE id=". $_GET['id']
    );
    $success = $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);

    if($success) {
      // SELECT success
      echo json_encode($result);
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
