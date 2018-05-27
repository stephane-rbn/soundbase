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
    $error = false;
    $listOfErrors = [];

    $_POST['description'] = trim($_POST['description']);

    if (strlen($_POST['description']) <= intval($_POST['maxLength'])) {

      $connection = connectDB();

      $query = $connection->prepare('UPDATE MEMBER SET description=:description WHERE id=:id');

      $query->execute([
        'description' => $_POST['description'],
        'id'          => $_SESSION['id']
      ]);

      unset($_POST['maxLength']);

      $_SESSION["successUpdate"]["description"] = true;

      header('Location: ../edit-profile.php');

    } else {
      // die("Your description exceeds 2500 characters");
      $error = true;
      $listOfErrors[] = 12;
    }

    if ($error) {
      $_SESSION["message"] = true;
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;

      header("Location: ../edit-profile.php");
    }

  } else {
    die("Error: invalid form submission.");
  }
