<?php

  session_start();
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 1
    && !empty($_POST["event_id"])
  ) {

    if (!is_numeric($_POST["event_id"])) {
      die("This event doesn't exist");
    }

    // Database connection
    $connection = connectDB();

    $ticketToken = createToken(64);

    // Query that inserts the registration
    $registrationQuery = $connection->prepare(
      "INSERT INTO registration (member, events, registration_token) VALUES (?, ?, ?)"
    );

    $registrationQuery->execute([
      $_SESSION['id'],
      $_POST["event_id"],
      $ticketToken
    ]);

    $_SESSION["registredInEvent"] = true;

    header("Location: ../event.php?id=" . $_POST["event_id"]);


  } else {
    die("Error: invalid form submission.");
  }
