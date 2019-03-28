<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 1 && !empty($_POST["post"])) {
      $error = false;
      $listOfErrors = [];

      $_POST["post"] = trim($_POST["post"]);

      if (strlen($_POST['post']) <= 280) {

      // Database connection
          $connection = connectDB();

          $query = $connection->prepare('INSERT INTO post (content, publication_date, member) VALUES (:content, :publication_date, :member)');

          $query->execute([
        "content"          => $_POST['post'],
        "publication_date" => date("Y-m-d H:i:s"),
        "member"           => $_SESSION["id"]
      ]);

          $_SESSION["post"] = true;
          header('Location: ../profile.php');
      } else {
          $error = true;
          $listOfErrors[] = 12;
          $_SESSION["message"] = true;
          $_SESSION["errorForm"] = $listOfErrors;
          $_SESSION["postForm"] = $_POST;

          header("Location: ../newPost.php");
      }
  } else {
      $_SESSION["message"] = true;
      header("Location: ../newPost.php");
  }
