<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if (count($_POST) === 1
    && !empty($_POST["name"])
  ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

    // Cleaning string values

    $_POST["name"] = trim($_POST["name"]);

    // Check values one by one

    // name length: min 3 max 60

    if (strlen($_POST["name"]) < 3 || strlen($_POST["name"]) > 60) {
      $error = true;
      $listOfErrors[] = 19;
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../newPlaylist.php");
    }

    // Else => insertion in database

    else {

      // Query that inserts the new track
      $query = $connection->prepare("INSERT INTO playlist (name,member) VALUES (:name, :member)");

      // Execute the query
      $query->execute([
        "name"  => $_POST["name"],
        "member" => $_SESSION["id"]
      ]);

      $_SESSION["newPlaylistCreated"] = true;

      header("Location: ../myPlaylists.php");
    }

  } else {
    die("Error: invalid form submission.");
  }
