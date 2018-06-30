<?php

  session_start();
  require "../functions.php";

  xssProtection();

  if(count($_POST) === 3 &&
    !empty($_POST['comment']) &&
    !empty($_POST['contentType']) &&
    !empty($_POST['contentId'])) {

    $connection = connectDB();

    $query = $connection->prepare(
      "INSERT INTO comment (content, publication_date, member, " . $_POST['contentType'] . ") VALUES (:content, :publication_date, :member, :" . $_POST['contentType'] . ")"
    );

    $success = $query->execute([
      'content' => $_POST['comment'],
      'publication_date' => date("Y-m-d H:i:s"),
      'member' => $_SESSION['id'],
      $_POST['contentType'] => $_POST['contentId']
    ]);

    if($success) {
      // INSERT success
      http_response_code(201);
    } else {
      // INSERT fail
      http_response_code(500);
    }
  } else {
    // Invalid form data
    http_response_code(400);
  }

?>
