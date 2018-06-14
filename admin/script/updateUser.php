<?php
  session_start();
  require "../../functions.php";

  if (!isConnected()) {
    http_response_code(404);
  }

  xssProtection();

  if (count($_POST) === 6
    && !empty($_POST["username"])
    && !empty($_POST["name"])
    && !empty($_POST["email"])
    && !empty($_POST["birthday"])
    && !empty($_POST["position"])
    && !empty($_POST["user_id"])
  ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

    // Cleaning string values

    $_POST["name"]     = trim($_POST["name"]);
    $_POST["email"]    = strtolower(trim($_POST["email"]));
    $_POST["username"] = strtolower(trim($_POST["username"]));

    // Check values one by one

    // name length: min 3 max 60

    if (strlen($_POST["name"]) < 2 || strlen($_POST["name"]) > 60) {
      $error = true;
      $listOfErrors[] = 1;
    }

    // SQL query to get the current username
    $currentUsernameQuery = $connection->prepare("SELECT username FROM member WHERE id=:id AND token=:token");

    // Execute
    $currentUsernameQuery->execute([
      "id"    => $_SESSION["id"],
      "token" => $_SESSION["token"]
    ]);

    // Fetch data with the query and returns an associative array
    $resultUsername = $currentUsernameQuery->fetch(PDO::FETCH_ASSOC);

    // Doesn't check username unicity if not changed
    if ($_POST["username"] !== $resultUsername["username"]) {

      // username length: min 3 max 60
      if (strlen($_POST["username"]) < 2 || strlen($_POST["username"]) > 20) {
        $error = true;
        $listOfErrors[] = 2;
      } else {
        // Check if this username already exists

        // Query that returns 1 every time it founds this email
        $query = $connection->prepare("SELECT 1 FROM member WHERE username= :username");

        // Execute
        $query->execute(["username" => $_POST["username"]]);

        // Fetch data with the query
        $result = $query->fetch();

        if (!empty($result)) {
          $error = true;
          $listOfErrors[] = 10;
        }
      }
    }

    // SQL query to get the current email
    $currentEmailQuery = $connection->prepare("SELECT email FROM member WHERE id=:id AND token=:token");

    // Execute
    $currentEmailQuery->execute([
      "id"    => $_SESSION["id"],
      "token" => $_SESSION["token"]
    ]);

    // Fetch data with the query and returns an associative array
    $resultEmail = $currentEmailQuery->fetch(PDO::FETCH_ASSOC);

    // Doesn't check email unicity if not changed
    if ($_POST["email"] !== $resultEmail["email"]) {

      // email : valid format
      if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $listOfErrors[] = 6;
      } else {
        // Check if this email address already exists

        // Query that returns 1 every time it founds this email
        $query = $connection->prepare("SELECT 1 FROM member WHERE email= :email");

        // Execute
        $query->execute(["email" => $_POST["email"]]);

        // Fetch data with the query
        $result = $query->fetch();

        if (!empty($result)) {
          $error = true;
          $listOfErrors[] = 7;
        }
      }
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../account.php");
    }

    // Else => insertion in database

    else {

      // Query that inserts the new member
      $updateQuery = $connection->prepare(
        "UPDATE member SET email=:email,name=:name,username=:username,birthday=:birthday,position=:position WHERE id=:id"
      );

      // Execute the query
      $updateQuery->execute([
        "email"    => $_POST["email"],
        "name"     => $_POST["name"],
        "username" => $_POST["username"],
        "birthday" => $_POST["birthday"],
        "position" => $_POST["position"],
        "id"       => $_POST["user_id"]
      ]);

      $_SESSION["successUpdate"]["userInfo"] = true;

      header("Location: ../user_edit.php?id=" . $_POST["user_id"]);
    }

  } else {
    http_response_code(400);
  }
