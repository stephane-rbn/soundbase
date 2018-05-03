<?php
  session_start();
  require "../functions.php";
  require "../conf.inc.php";

  xssProtection();

  if (count($_POST) === 3
    && !empty($_POST["currentPwd"])
    && !empty($_POST["pwd"])
    && !empty($_POST["pwdConfirm"])
   ) {
    $error = false;
    $listOfErrors = [];

    // Database connection
    $connection = connectDB();

    // Query that gets the member's password
    $queryPassword = $connection->prepare("SELECT password FROM MEMBER WHERE id=:id AND token=:token");

    $queryPassword->execute([
      "id"    => $_SESSION["id"],
      "token" => $_SESSION["token"]
    ]);

    $result = $queryPassword->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($_POST["currentPwd"], $result["password"])) {
      $error = true;
      $listOfErrors[] = 11;
    }

    // Check password length: min 8 max 40

    if (strlen($_POST["pwd"]) < 8 || strlen($_POST["pwd"]) > 40) {
      $error = true;
      $listOfErrors[] = 8;
    }

    // Check if both passwords are identical

    if ($_POST["pwd"] !== $_POST["pwdConfirm"]) {
      $error = true;
      $listOfErrors[] = 9;
    }

    if ($error) {
      $_SESSION["errorForm"] = $listOfErrors;
      header("Location: ../account.php");
    }

    // Else => insertion in database

    else {

      // Query that update the member's token and password
      $query = $connection->prepare("UPDATE MEMBER SET password=?, token=? WHERE id=? AND token=?");

      $newToken = createToken();

      // Execute the query
      $query->execute([
        password_hash($_POST["pwd"], PASSWORD_DEFAULT),
        $newToken,
        $_SESSION["id"],
        $_SESSION["token"]
      ]);

      $_SESSION["token"] = $newToken;

      // header("Location: ../home.php");
      echo "You password has changed with success!";
    }

  } else {
    die("Error: invalid form submission.");
  }
