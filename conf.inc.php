<?php

require_once "dbConst.php";

$listOfErrors = [
  1  => "Name length must be between 2 and 60 characters",
  2  => "Username length must be between 2 and 20 characters",
  3  => "Wrong date format",
  4  => "This date doesn't exist",
  5  => "You must be 18 and 150 years old",
  6  => "Email not valid",
  7  => "This email already exists",
  8  => "Password length must be between 8 and 40 characters",
  9  => "Confirmation password doesn't match",
  10 => "Username already taken",
  11 => "Wrong password",
  12 => "Your description exceeds 2500 characters",
  13 => "Your file is too big!",
  14 => "There was an error uploading your file!",
  15 => "You can not upload files of this type!"
];
