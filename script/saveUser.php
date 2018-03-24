<?php
  // session_start();

  require "../functions.php";
  require "../conf.inc.php";

  if (count($_POST) === 8
    && !empty($_POST["firstName"])
    && !empty($_POST["lastName"])
    && !empty($_POST["birthday"])
    && !empty($_POST["musicianName"])
    && !empty($_POST["email"])
    && !empty($_POST["pwd"])
    && !empty($_POST["pwdConfirm"])
    && !empty($_POST["cgu"])
  ) {
    $error = false;
    $listOfErrors = [];

    // Cleaning string values

    $_POST["firstName"]    = ucfirst(strtolower(trim($_POST["firstName"])));
    $_POST["lastName"]     = strtoupper(trim($_POST["lastName"]));
    $_POST["musicianName"] = trim($_POST["musicianName"]);
    $_POST["email"]        = strtolower(trim($_POST["email"]));

    // Check values one by one

    // firstName length: min 3 max 60

    if (strlen($_POST["firstName"]) < 3 || strlen($_POST["firstName"]) > 60) {
      $error = true;
      $listOfErrors[] = 1;
    }

    // lastName length: min 3 max 60

    if (strlen($_POST["lastName"]) < 3 || strlen($_POST["lastName"]) > 60) {
      $error = true;
      $listOfErrors[] = 2;
    }

    // musicianName length: min 2 max 254

    if (strlen($_POST["musicianName"]) < 2 || strlen($_POST["musicianName"]) > 60) {
      $error = true;
      $listOfErrors[] = 3;
    }

    // email : valid format

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
      $error = true;
      $listOfErrors[] = 4;
    } else {
      // Check if this email address already exists

      // Database connection
      $connection = connectDB();

      // Query that returns 1 every time it founds this email
      $query = $connection->prepare("SELECT 1 FROM MEMBER WHERE email= :email");

      // Execute
      $query->execute(["email" => $_POST["email"]]);

      // Fetch data with the query
      $result = $query->fetch();

      if (!empty($result)) {
        $error = true;
        $listOfErrors[] = 5;
      }
    }

    // Check date format: american (YYYY-MM-DD) or european (DD/MM/YYYY)

    $dateFormat = false;

    if (strpos($_POST["birthday"], "/")) {
      list($day, $month, $year) = explode("/", $_POST["birthday"]);
      $dateFormat = true;
    } else if (strpos($_POST["birthday"], "-")) {
      list($year, $month, $day) = explode("-", $_POST["birthday"]);
      $dateFormat = true;
    } else {
      $error = true;
      $listOfErrors[] = 6;
    }

    // Check valid date

    if (!is_numeric($month)
      || !is_numeric($day)
      || !is_numeric($year)
      || !checkdate($month, $day, $year)
    ) {
      $error = true;
      $listOfErrors[] = 7;
    } else {
      // Check if allowed to signup (13 <= age <= 150)
      $today        = time();
      $time13years  = $today - 13*3600*24*365;
      $time100years = $today - 150*3600*24*365;

      // Returns UNIX timestamp with corresponding to the arguments given
      $birthday = mktime(0, 0, 0, $month, $day, $year);

      if ($time13years < $birthday || $time150years > $birthday) {
        $error = true;
        $listOferrors[] = 8;
      }
    }

    // Check password length: min 8 max 40

    if (strlen($_POST["pwd"]) < 8 || strlen($_POST["pwd"]) > 40) {
      $error = true;
      $listOfErrors[] = 9;
    }

    // Check if both passwords are identical

    if ($_POST["pwd"] != $_POST["pwdConfirm"]) {
      $error = true;
      $listOfErrors[] = 10;
    }

    if ($error) {
      // $_SESSION["errorForm"] = $listOfErrors;
      // $_SESSION["postForm"] = $_POST;
      // header("Location: ../signup.php");
      foreach ($listOfErrors as $error) {
        echo $error . "<br>";
      }
    }

    // Else => insertion in database

    else {

      // Query that inserts the new member
      $query = $connection->prepare("INSERT INTO MEMBER (email, first_name,last_name,musician_name,birthday,password,profile_photo_filename,cover_photo_filename) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

      $pwd = password_hash($pwd, PASSWORD_DEFAULT);

      // Execute the query
      $query->execute([
        $_POST["email"],
        $_POST["firstName"],
        $_POST["lastName"],
        $_POST["musicianName"],
        $year . "-" . $month . "-" . $day,
        $pwd,
        "photo.png",
        "cover.png"
      ]);
    }

    header("Location: ../home.php");

  } else {
    die("Error: invalid form submission.");
  }
