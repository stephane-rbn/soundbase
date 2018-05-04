<?php

require_once "conf.inc.php";

// Connection to database
function connectDB(){

  $dsn = DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME;

  try {
    $connection = new PDO($dsn, DBUSER, DBPWD);
  } catch (Exception $e) {
    die("SQL error:" . $e->getMessage());
  }

  return $connection;
}

// Check if a user is connected
function isConnected() {

  if (isset($_SESSION["auth"])) {
    return true;
  }

  return false;
}

// Generate a token
function createToken() {
  // random_bytes() returns a cryptographic secure pseudo-random bytes (string)
  // bin2hex() returns a converted binary data in hexadecimal representation
  return bin2hex(random_bytes(32));
}

// Fill account's form fields when submission failed
function fillSessionFieldSettings($field) {
  // global keyword lets this function access $result variable
  global $result;
  return isset($_SESSION["postForm"]) ? $_SESSION["postForm"][$field] : $result[$field];
}

// Fill signup form fields when submission failed
function fillSessionField($field) {
  return isset($_SESSION["postForm"]) ? $_SESSION["postForm"][$field] : "";
}

// Display error messages in signup form (below fields)
function isErrorPresent($errorNumber) {
  if (isset($_SESSION["errorForm"])) {
    for ($i = 0; $i < count($_SESSION["errorForm"]); $i++) {
      foreach ($_SESSION["errorForm"] as $key) {
        if ($errorNumber === $key) {
          return true;
        }
      }
    }
  }

  return false;
}

// Display login error message when submission failed
function loginErrorMessage() {
  if (isset($_SESSION["message"])) {

    $message = "Error: please fill all fields";

    echo '<div class="push"></div>';

    echo '<div class="alert alert-warning" role="alert">' . $message . '</div>';
    unset($_SESSION["message"]);
  }
}

function successfulUpdateMessage() {
  if (isset($_SESSION["successUpdate"]["userInfo"])) {

    $message = 'Your information has been updated';

    echo '<div class="push"></div>';

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert"> ' . $message .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button></div>';
  } else if (isset($_SESSION["successUpdate"]["userPassword"])) {

    $message = 'Password successfully updated';

    echo '<div class="push"></div>';

    echo '<div class="alert alert-success alert-dismissible fade show" role="alert"> ' . $message .
        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span></button></div>';
  }
}

// Check if a user is admin
function isAdmin() {

  $connection = connectDB();

  $query = $connection->prepare(
    "SELECT position FROM MEMBER WHERE id=:toto"
  );

  $query->execute([
    "toto" => $_SESSION["id"],
  ]);

  $result = $query->fetch(PDO::FETCH_ASSOC);

  $_SESSION["admin"] = $result["position"];

  if ($_SESSION["admin"] === '1') {
    return true;
  }

  return false;
}

function sqlSelect($query) {
  $connection = connectDB();
  $sql = $connection->prepare($query);
  $sql->execute();
  $result = $sql->fetchAll(PDO::FETCH_ASSOC);

  return $result;
}

// Prevent XSS attacks by cleaning values posted before the main treatment of this data
function xssProtection() {
  foreach ($_POST as $key => $value) {
    // Convert special characters to HTML entities
    $_POST[$key] = htmlspecialchars($value);
  }

  foreach ($_GET as $key => $value) {
    // Convert special characters to HTML entities
    $_GET[$key] = htmlspecialchars($value);
  }
}
