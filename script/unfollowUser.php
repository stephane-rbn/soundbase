<?php
  session_start();
  require "../functions.php";

  if (!isConnected()) {
      http_response_code(404);
  } else {

    $connection = connectDB();

    // Query that deletes the subscription from the database
    $query = $connection->prepare("DELETE FROM subscription WHERE member_following= :member_following AND member_followed= :member_followed");

    // Execute the query
    $query->execute([
      "member_following"=> $_SESSION["id"],
      "member_followed" => $_GET["id"]
    ]);

    header("Location: ../profile.php?username=" . $_GET["username"]);
  }
