<?php
  session_start();

  require "functions.php";

  $connection = connectDB();

  xssProtection();

  if (!empty($_GET['username'])) {
    $listOFmember = sqlSelect("SELECT 1 FROM member WHERE username='" . $_GET['username'] ."'");
    if (!empty($listOFmember)) {
      header("Location: profile.php?username=" . $_GET['username']);
    } else {
      include "head.php";
      include "navbar.php";

      echo "<br>";

      $searchQuery = $_GET['username'];
      $userSearch = sqlSelectFetchAll("SELECT * FROM member WHERE (`username` LIKE '%" . $searchQuery . "%') OR (`name` LIKE '%".$searchQuery."%') ");

      foreach ($userSearch as $user) {
        echo "<center>";
          echo "<div class='row'>";
            echo "<div class='col-md-2'>";
              if ($user['profile_photo_filename']!="photo.png") {
                echo "<center><a href='profile.php?username=" . $user["username"] . "'><img src='uploads/member/avatar/" . $user["profile_photo_filename"] . "' alt='profile picture' height=50 width=50></a></center>";
              } else {
                echo "<center><a href='profile.php?username=" . $user["username"] . "'><img src='http://placehold.it/200x200&text=Logo' alt='profile picture' height=50 width=50></a></center>";
              }
            echo "</div>";
            echo "<div class='col-xs-2'>";
              echo "<center><h5><b>name : </b></h5></center>";
            echo "</div>";
            echo "<div class='col-md-2'>";
              echo "<center><h5>" . $user["name"] . "</h5></center>";
            echo "</div>";
            echo "<div class='col-md-2'>";
              echo "<center><h5><b>username : </b></h5></center>";
            echo "</div>";
            echo "<div class='col-md-1'>";
              echo "<center><h5>" . $user["username"] . "</h5></center>";
            echo "</div>";
            echo "<div class='col-md-2'>";
              echo "<center><h5><a href='profile.php?username=" . $user["username"] . "'>Profile</a></h5></center>";
            echo "</div>";
          echo "</div>";
        echo "</center>";
        echo "<hr>";
      }
    }
  } else {
    header("Location: home.php");
  }

  include "footer.php";
