<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";
  // Connection to database
  $connection = connectDB();

  header('Content-Type: application/json');

  // Connection to database
  $connection = connectDB();


    if(isset($_GET['track'])){
      $stmt = $connection->query('SELECT * FROM comment WHERE track='. $_GET['track'] . ' ORDER BY publication_date DESC');
    }
    else if(isset($_GET['event'])){
      $stmt = $connection->query('SELECT * FROM comment WHERE event='. $_GET['event'] . ' ORDER BY publication_date DESC');
    }
    else if(isset($_GET['post'])){
      $stmt = $connection->query('SELECT * FROM comment WHERE post='. $_GET['post'] . ' ORDER BY publication_date DESC');
    }
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);



  echo json_encode($res);
