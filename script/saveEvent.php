<?php
  session_start();
  require_once "../functions.php";

  xssProtection();

  if (((count($_POST) === 6
    && !empty($_POST["name"])
    && !empty($_POST["event_date"])
    && !empty($_FILES["image"])
    && !empty($_POST["capacity"])
    && !empty($_POST["address"])
    && !empty($_POST["description"]))
    || ((count($_POST) === 5)
    && !empty($_POST["name"])
    && !empty($_POST["event_date"])
    && !empty($_FILES["image"])
    && !empty($_POST["capacity"])
    && !empty($_POST["description"])))
  ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

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

    $fileName    = $_FILES['image']['name'];
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileSize    = $_FILES['image']['size'];
    $fileError   = $_FILES['image']['error'];

    // Split the file name into an array by the separating the string into substrings using the '.' character
    $fileNameArray = explode('.', $fileName);

    // Get the last element of the array and make it in lower case
    $fileExtension = strtolower(end($fileNameArray));

    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];

    // Check if the given file owns an allowed extension
    if (in_array($fileExtension, $allowedExtensions)) {

      // Check error at upload
      if ($fileError === 0) {

        // Check if the file size doesn't exceed 2MB
        if ($fileSize < 2097152) {

          $fileNewName     = $_SESSION['id'] . "-" . uniqid() . "." . $fileExtension;
          $fileDestination = "../uploads/events/backgrounds/" . $fileNewName;

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

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      $_SESSION["postForm"] = $_POST;
      header("Location: ../newEvent.php");
    }

    // Else => insertion in database

    else {

      if (isset($_POST["address"])) {

        // Query that inserts the new event
        $query = $connection->prepare("INSERT INTO events (name,description,capacity,event_date,member,background_filename,address,publication_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        // Execute the query
        $query->execute([
          $_POST["name"],
          $_POST["description"],
          $_POST["capacity"],
          $year . "-" . $month . "-" . $day,
          $_SESSION["id"],
          $fileNewName,
          $_POST["address"],
          date("Y-m-d H:i:s")
        ]);
      } else {

        // Query that inserts the new event without address
        $query = $connection->prepare("INSERT INTO events (name,description,capacity,event_date,member,background_filename,publication_date) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Execute the query
        $query->execute([
          $_POST["name"],
          $_POST["description"],
          $_POST["capacity"],
          $year . "-" . $month . "-" . $day,
          $_SESSION["id"],
          $fileNewName,
          date("Y-m-d H:i:s")
        ]);
      }

      $_SESSION["newEventAdded"] = true;

      header("Location: ../events.php");
    }

  } else {
    die("Error: invalid form submission.");
  }
