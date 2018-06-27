<?php
  session_start();
  require "../../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../../login.php");
  }

  xssProtection();

  if (!empty($_POST["name"])
    && !empty($_POST["event_date"])
    && !empty($_POST["capacity"])
    && !empty($_POST["description"])
    && !empty($_POST["event_id"])
  ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

    $eventQuery = $connection->prepare(
      "SELECT * FROM events WHERE id= {$_POST["event_id"]}"
    );

    $eventQuery->execute();

    $eventInfo = $eventQuery->fetch(PDO::FETCH_ASSOC);

    // Cleaning string values

    $_POST["name"]        = trim($_POST["name"]);
    $_POST["description"] = trim($_POST["description"]);

    if (!empty($_POST["address"])) {
      $_POST["address"] = trim($_POST["address"]);
    }

    // Check values one by one

    // name length: min 2 max 60

    if (strlen($_POST["name"]) < 2 || strlen($_POST["name"]) > 60) {
      $error = true;
      $listOfErrors[] = 1;
    }

    // address length: min 3 max 100
    if (!empty($_POST["address"])) {
      if (strlen($_POST["address"]) < 3 || strlen($_POST["address"]) > 100) {
        $error = true;
        $listOfErrors[] = 21;
      }
    }

    // Check date format: american (YYYY-MM-DD) or european (DD/MM/YYYY)

    $dateFormat = false;

    if (strpos($_POST["event_date"], "/")) {
      list($day, $month, $year) = explode("/", $_POST["event_date"]);
      $dateFormat = true;
    } else if (strpos($_POST["event_date"], "-")) {
      list($year, $month, $day) = explode("-", $_POST["event_date"]);
      $dateFormat = true;
    } else {
      $error = true;
      $listOfErrors[] = 3;
    }

    // Check valid date

    if (!is_numeric($month)
      || !is_numeric($day)
      || !is_numeric($year)
      || !checkdate($month, $day, $year)
      ) {
      $error = true;
      $listOfErrors[] = 4;
    } else {
      // Check if the day chosen is not a past day

      // Returns UNIX timestamp with corresponding to the arguments given
      $chosenDate = mktime(0, 0, 0, $month, $day, $year);

      if ($chosenDate < time()) {
        $error = true;
        $listOfErrors[] = 22;
      }
    }

    // capacity must be >= 1
    if ($_POST["capacity"] < 1 || !is_numeric($_POST["capacity"])) {
      $error = true;
      $listOfErrors[] = 23;
    }

    if (strlen($_POST['description']) > 2500) {
      $error = true;
      $listOfErrors[] = 12;
    }

    if (!empty($_FILES["background_image"])) {
      $fileName    = $_FILES['background_image']['name'];
      $fileTmpName = $_FILES['background_image']['tmp_name'];
      $fileSize    = $_FILES['background_image']['size'];
      $fileError   = $_FILES['background_image']['error'];

      // Split the file name into an array by the separating the string into substrings using the '.' character
      $fileNameArray = explode('.', $fileName);

      // Get the last element of the array and make it in lower case
      $fileExtension = strtolower(end($fileNameArray));

      $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];


      if ($fileError !== 4) {

        // Check if the given file owns an allowed extension
        if (in_array($fileExtension, $allowedExtensions)) {

          // Check error at upload
          if ($fileError === 0) {

            // Check if the file size doesn't exceed 2MB
            if ($fileSize < 2097152) {

              $fileNewName     = $eventInfo["member"] . "-" . uniqid() . "." . $fileExtension;
              $fileDestination = "../../uploads/events/backgrounds/" . $fileNewName;

              // Move the upload file from its tmp directory to its final destination
              // $result's value is true when the move succeeds
              $result = move_uploaded_file($fileTmpName, $fileDestination);

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
      }
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../event_edit.php?id=" . $_POST["event_id"]);
    }

    // Else => insertion in database

    else {

      if (!empty($_POST["address"]) && ($fileError === 4 || empty($_FILES["background_image"]))) {

        // Query that updates the new event with a new address and no new background_image
        $query = $connection->prepare(
          "UPDATE events SET name=:name,description=:description,capacity=:capacity,event_date=:event_date,address=:address WHERE id=:id
        ");

        // Execute the query
        $query->execute([
          "name"        => $_POST["name"],
          "description" => $_POST["description"],
          "capacity"    => $_POST["capacity"],
          "event_date"  => $year . "-" . $month . "-" . $day,
          "address"     => $_POST["address"],
          "id"          => $_POST["event_id"]
        ]);

        $_SESSION["successUpdate"]["eventInfo"] = true;

        header("Location: ../event_edit.php?id=" . $_POST["event_id"]);

      } else {

        // Query that updates the new event with a new background_image
        $query = $connection->prepare(
          "UPDATE events SET name=:name,description=:description,capacity=:capacity,event_date=:event_date,background_filename=:background_filename,address=:address WHERE id=:id"
        );

        // Execute the query
        $query->execute([
          "name"                => $_POST["name"],
          "description"         => $_POST["description"],
          "capacity"            => $_POST["capacity"],
          "event_date"          => $year . "-" . $month . "-" . $day,
          "background_filename" => $fileNewName,
          "address"             => $_POST["address"],
          "id"                  => $_POST["event_id"]
        ]);

        $_SESSION["successUpdate"]["eventInfo"] = true;

        header("Location: ../event_edit.php?id=" . $_POST["event_id"]);
      }
    }

  } else {
    $_SESSION["message"] = true;
    header("Location: ../event_edit.php?id=" . $_POST["event_id"]);
  }
