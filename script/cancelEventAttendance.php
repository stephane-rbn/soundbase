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

      // Query that inserts the registration
      $query = $connection->prepare(
      "DELETE FROM registration WHERE member={$_SESSION['id']} AND events={$_POST["event_id"]}"
    );

      $query->execute();

      $_SESSION["cancelledAttendance"] = true;

      header("Location: ../event.php?id=" . $_POST["event_id"]);
  } else {
      die("Error: invalid form submission.");
  }
