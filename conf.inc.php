<?php

require_once "dbConst.php";

date_default_timezone_set('Europe/Paris');

$listOfErrors = [
  1  => "Name length must be between 2 and 60 characters",
  2  => "Username length must be between 2 and 20 characters",
  3  => "Wrong date format",
  4  => "This date doesn't exist",
  5  => "Must be 18 and 150 years old",
  6  => "Email not valid",
  7  => "This email already exists",
  8  => "Password length must be between 8 and 40 characters",
  9  => "Confirmation password doesn't match",
  10 => "Username already taken",
  11 => "Wrong password",
  12 => "Your description exceeds the limit",
  13 => "Your file is too big!",
  14 => "There was an error uploading your file!",
  15 => "You can not upload files of this type!",
  16 => "Your file is too big!",
  17 => "There was an error uploading your file!",
  18 => "You can not upload files of this type!",
  19 => "The length must be between 3 and 60 characters",
  20 => "The genre is not correct",
  21 => "The length must be between 3 and 100 characters",
  22 => "You can not choose a past day",
  23 => "Please enter a valid capacity",
  24 => "This position doesn't exist"
];

$defaultGenre = "rb";

$listOfGenres = [
  "rb" => "R&B",
  "rk" => "Rock",
  "pp" => "Pop",
  "hh" => "Hip Hop",
  "rg" => "Reggae",
  "jz" => "Jazz",
  "cm" => "Classical music"
];

$listOfPeriods = [
  "year" => "Last year",
  "month" => "Last month",
  "week" => "Last week",
  "day" => "Last day",
  "hour" => "Last hour"
];
