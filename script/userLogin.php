<?php
  session_start();
  require_once "../conf.inc.php";
  require_once "../functions.php";

  xssProtection();

  if (count($_POST) === 2 && !empty($_POST["email"] && !empty($_POST["pwd"]))) {

    $_POST["email"] = strtolower($_POST["email"]);

    // Connection to database
    $connection = connectDB();

    // Query that get the password that matching with the email given
    $query = $connection->prepare("SELECT * FROM MEMBER WHERE email=:toto");

    // Execute the query
    $query->execute([
      "toto" => $_POST["email"],
    ]);

    // Fetch data with the query and get it as an associative array
    $data = $query->fetch(PDO::FETCH_ASSOC);

    if (password_verify($_POST["pwd"], $data["password"])) {
      $_SESSION["auth"]  = true;
      $_SESSION["id"]    = $data["id"];
      $_SESSION["token"] = $data["token"];

      header("Location: ../home.php");
    } else {
      $_SESSION["message"] = "Erreur : l'email ou le mot de passe ne correspond pas";
      header("Location: ../login.php");
    }

  } else {
    $_SESSION["message"] = "Erreur : veuillez renseigner votre email et votre mot de passe";
    header("Location: ../login.php");
  }
?>
