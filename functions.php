<?php

require_once "conf.inc.php";

function connectDB(){

  $dsn = DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME;

  try {
    $connection = new PDO($dsn, DBUSER, DBPWD);
  } catch (Exception $e) {
    die("SQL error:" . $e->getMessage());
  }

  return $connection;
}

function isConnected() {

  if (isset($_SESSION["auth"])) {
    return true;
  }

  return false;
}

function createToken() {
  // random_bytes() returns a cryptographic secure pseudo-random bytes (string)
  // bin2hex() returns a converted binary data in hexadecimal representation
  return bin2hex(random_bytes(32));
}
