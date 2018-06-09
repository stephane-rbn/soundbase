<?php

  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if (count($_POST) === 1
    && !empty($_POST["event_id"])
  ) {

    if (!is_numeric($_POST["event_id"])) {
      die("This event doesn't exist");
    }

    // Database connection
    $connection = connectDB();

    // Query that inserts the registration
    $registrationQuery = $connection->prepare(
      "INSERT INTO registration (member, events) VALUES (" . $_SESSION['id'] . ", " . $_POST["event_id"] .")"
    );

    $registrationQuery->execute();

    $_SESSION["registredInEvent"] = true;

    header("Location: ../event.php?id=" . $_POST["event_id"]);


  } else {
    die("Error: invalid form submission.");
  }
