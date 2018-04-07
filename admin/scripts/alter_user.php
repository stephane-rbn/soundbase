<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

      // Query that inserts the new member
      $query = $connection->prepare("UPDATE MEMBER SET first_name=? WHERE email='stanislas.lange@protonmail.com'");

      // Execute the query
      $query->execute([
        $_POST["first_name"]
      ]);

?>
