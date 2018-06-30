<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";
  // Connection to database
  $connection = connectDB();

  header('Content-Type: application/json');

  // Connection to database
  $connection = connectDB();

  $query = $connection->prepare ('SELECT * FROM member WHERE id='. $_GET['id']);
    // Execute the query
  $query->execute();

  // Fetch data with the query and get it as an associative array
  $result = $query->fetch(PDO::FETCH_ASSOC);

  echo json_encode($result);
