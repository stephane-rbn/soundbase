<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  // Connection to database
  $connection = connectDB();
  var_dump($_POST);
if(count($_POST) === 1 &&
   !empty($_POST['comment'])
    ) {

  $db = connectDB();
  if(isset($_GET['track'])){
    $stmt = $db->prepare('INSERT INTO comment (content, publication_date, member, track) VALUES (:content, :publication_date, :member, :track)');
    $res = $stmt->execute([
    'content' => $_POST['comment'],
    'publication_date' => date("Y-m-d H:i:s"),
    'member' => $_SESSION['id'],
    'track' => $_GET['track']
  ]);

  }
  else if(isset($_GET['event'])){
    $stmt = $db->prepare('INSERT INTO comment (content, publication_date, member, event) VALUES (:content, :publication_date, :member, :event)');
    $res = $stmt->execute([
    'content' => $_POST['comment'],
    'publication_date' => date("Y-m-d H:i:s"),
    'member' => $_SESSION['id'],
    'event' => $_GET['event']
  ]);

  }
  else if(isset($_GET['post'])){
    $stmt = $db->prepare('INSERT INTO comment (content, publication_date, member, post) VALUES (:content, :publication_date, :member, :post)');
    $res = $stmt->execute([
    'content' => $_POST['comment'],
    'publication_date' => date("Y-m-d H:i:s"),
    'member' => $_SESSION['id'],
    'post' => $_GET['post']
  ]);

  }

  if($res) {
    http_response_code(201);
  } else {
    http_response_code(500);
  }
} else {
  http_response_code(400);
}




