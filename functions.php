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
function createToken($length) {
  // random_bytes() returns pseudo-random bytes (string)
  // bin2hex() returns converted binary data in hexadecimal representation
  // bin2hex(random_bytes($int)) returns a string of length $int * 2
  return bin2hex(random_bytes($length / 2));
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
    if (in_array($errorNumber, $_SESSION["errorForm"])) {
      return true;
    }
  }

  return false;
}

// Display error message asking user to fill all fields correctly
function fillAllFieldsErrorMessage() {
  if (isset($_SESSION["message"])) {

    $message = "Error: please fill all fields";

    echo '<div class="push"></div>';

    echo '<div class="alert alert-warning" role="alert">' . $message . '</div>';
    unset($_SESSION["message"]);
  }
}

// Display the sucess messages
function successfulUpdateMessage() {
  if (isset($_SESSION["successUpdate"]["userInfo"])) {
    $message = 'Your information has been updated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["userPassword"])) {
    $message = 'Password successfully updated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["description"])) {
    $message = 'Description successfully updated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["avatar"])) {
    $message = 'Profile avatar successfully updated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["cover"])) {
    $message = 'Profile cover successfully updated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["newTrackAdded"])) {
    $message = 'The new track has been successfully uploaded';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["newEventAdded"])) {
    $message = 'The new event has been successfully created';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["registredInEvent"])) {
    $message = 'You have successfully been added to the event attendees list';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["cancelledAttendance"])) {
    $message = 'You have successfully been removed from the event attendees list';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["sucessDeletion"])) {
    $message = 'The item has been sucessfully deleted';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["post"])) {
    $message = 'The new post has been successfully created';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["userInfo"])) {
    $message = 'This user info has been sucessfully udpated';
    alertSuccessMessage($message);
  } else if (isset($_SESSION["successUpdate"]["eventInfo"])) {
    $message = 'This event has been sucessfully udpated';
    alertSuccessMessage($message);
  }
}

// Display a success message specified in successfulUpdateMessage() function
function alertSuccessMessage($message){
  echo '<div class="push"></div>';

  echo '<div class="alert alert-success alert-dismissible show" role="alert">' . $message .
      '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span></button></div>';
}

// Check if a user is admin
function isAdmin() {

  $connection = connectDB();

  $query = $connection->prepare(
    "SELECT position FROM member WHERE id=:toto"
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

// Return an associative array containing the elements selectionned
function sqlSelect($query) {
  $connection = connectDB();
  $sql = $connection->prepare($query);
  $sql->execute();
  $result = $sql->fetch(PDO::FETCH_ASSOC);

  return $result;
}

// Return an array of associative arrays containing the elements selectionned
function sqlSelectFetchAll($query) {
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

// Return the number of people following the member with id in $member
function countFollower($member) {
  $connection = connectDB();
  $following = sqlSelect("SELECT COUNT(*) as count FROM subscription WHERE member_followed=" . $member);
  return $following["count"];
}

// Return the number of followers that the member with id in $member owns
function countFollowing($member) {
  $connection = connectDB();
  $followers = sqlSelect("SELECT COUNT(*) as count FROM subscription WHERE member_following=" . $member);
  return $followers["count"];
}
