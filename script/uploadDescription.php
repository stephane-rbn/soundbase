<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if (count($_POST) === 3
    && isset($_POST['submit-description'])
    && isset($_POST['maxLength'])
    && !empty($_POST['description'])
  ) {
    if (strlen($_POST['description']) <= $_POST['maxLength']) {

      $connection = connectDB();

      $query = $connection->prepare('UPDATE MEMBER SET description=:description WHERE id=:id');

      $query->execute([
        'description' => $_POST['description'],
        'id'          => $_SESSION['id']
      ]);

      unset($_POST['maxLength']);

      header('Location: ../profile.php');

    } else {
      die("Your description exceeds 2500 characters");
    }
  } else {
    die("Error: invalid form submission.");
  }
