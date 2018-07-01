<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 3
    && !empty($_POST["title"])
    && !empty($_POST["genre"])
    && !empty($_FILES["track"])
    && !empty($_FILES["cover"])
    && !empty($_POST["description"])
  ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

    // Cleaning string values

    $_POST["title"]       = trim($_POST["title"]);
    $_POST['description'] = trim($_POST['description']);

    // Check values one by one

    // name length: min 3 max 60

    if (strlen($_POST["title"]) < 3 || strlen($_POST["title"]) > 60) {
      $error = true;
      $listOfErrors[] = 19;
    }

    // genres
    if (!array_key_exists($_POST["genre"], $listOfGenres)){
      $error = true;
      $listOfErrors[] = 20;
    }

    $coverFileName    = $_FILES['cover']['name'];
    $coverfileTmpName = $_FILES['cover']['tmp_name'];
    $coverFileSize    = $_FILES['cover']['size'];
    $coverFileError   = $_FILES['cover']['error'];

    $trackFileName    = $_FILES['track']['name'];
    $trackFileTmpName = $_FILES['track']['tmp_name'];
    $trackFileSize    = $_FILES['track']['size'];
    $trackFileError   = $_FILES['track']['error'];

    // Split the file name into an array by the separating the string into substrings using the '.' character
    $coverFileNameArray = explode('.', $coverFileName);
    $trackFileNameArray = explode('.', $trackFileName);

    // Get the last element of the array and make it in lower case
    $coverFileExtension = strtolower(end($coverFileNameArray));
    $trackFileExtension = strtolower(end($trackFileNameArray));

    $coverAllowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    $trackAllowedExtensions = ['mp3', 'wav', 'ogg'];

    // Check if the given file owns an allowed extension
    if (in_array($coverFileExtension, $coverAllowedExtensions)) {

      // Check error at upload
      if ($coverFileError === 0) {

        // Check if the file size doesn't exceed 2MB
        if ($coverFileSize < 2097152) {

          $coverFileNewName     = $_SESSION['id'] . "-" . uniqid() . "." . $coverFileExtension;
          $coverFileDestination = "../uploads/tracks/album_cover/" . $coverFileNewName;

          // Move the upload file from its tmp directory to its final destination
          // $result's value is true when the move succeeds
          $result = move_uploaded_file($coverfileTmpName, $coverFileDestination);

        } else {
          $error = true;
          $listOfErrors[] = 13;
        }

      } else {
        $error = true;
        $listOfErrors[] = 14;
      }

    } else {
      $error = true;
      $listOfErrors[] = 15;
    }

    // Check if the given file owns an allowed extension
    if (in_array($trackFileExtension, $trackAllowedExtensions)) {

      // Check error at upload
      if ($trackFileError === 0) {

        // Check if the file size doesn't exceed 2MB
        if ($trackFileSize < 20971520) {

          $trackFileNewName     = $_SESSION['id'] . "-" . uniqid() . "." . $trackFileExtension;
          $trackFileDestination = "../uploads/tracks/files/" . $trackFileNewName;

          // Move the upload file from its tmp directory to its final destination
          // $result's value is true when the move succeeds
          $result = move_uploaded_file($trackFileTmpName, $trackFileDestination);

        } else {
          $error = true;
          $listOfErrors[] = 16;
        }

      } else {
        $error = true;
        $listOfErrors[] = 17;
      }

    } else {
      $error = true;
      $listOfErrors[] = 18;
    }

    if (strlen($_POST['description']) > 200) {
      $error = true;
      $listOfErrors[] = 12;
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../newTrackForm.php");
      // die("Error: il y a plusieurs erreurs.");
    }

    // Else => insertion in database

    else {

      // Query that inserts the new track
      $query = $connection->prepare("INSERT INTO track (title,description,genre,photo_filename,track_filename,publication_date,member) VALUES (:title, :description, :genre, :photo_filename, :track_filename, :publication_date, :member)");

      // Execute the query
      $query->execute([
        "title"            => $_POST["title"],
        "description"      => $_POST["description"],
        "genre"            => $_POST["genre"],
        "photo_filename"   => $coverFileNewName,
        "track_filename"   => $trackFileNewName,
        "publication_date" => date("Y-m-d H:i:s"),
        "member"           => $_SESSION["id"]
      ]);

      $_SESSION["newTrackAdded"] = true;

      header("Location: ../profile.php");
    }

  } else {
    die("Error: invalid form submission.");
  }
