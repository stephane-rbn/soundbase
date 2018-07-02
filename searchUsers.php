<?php
  session_start();

  require_once "functions.php";

  xssProtection();

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
      $dataSearch = sqlSelectFetchAll("(SELECT member.id,member.name, member.username, member.profile_photo_filename, NULL as title, NULL as photo_filename FROM member WHERE (`username` LIKE '%" . $searchQuery . "%') OR (`name` LIKE '%".$searchQuery."%')) UNION (SELECT track.id, NULL as name, NULL as username, NULL as profile_photo_filename, track.title, track.photo_filename FROM track WHERE (`title` LIKE '%" . $searchQuery . "%'))");

      foreach ($dataSearch as $data) {
        if(isset($data['profile_photo_filename'])){
          echo "<center>";
            echo "<div class='row'>";
              echo "<div class='col-md-2'>";
                if ($data['profile_photo_filename']!="photo.png") {
                  echo "<center><a href='profile.php?username=" . $data["username"] . "'><img src='uploads/member/avatar/" . $data["profile_photo_filename"] . "' alt='profile picture' height=50 width=50></a></center>";
                } else {
                  echo "<center><a href='profile.php?username=" . $data["username"] . "'><img src='http://placehold.it/200x200&text=Logo' alt='profile picture' height=50 width=50></a></center>";
                }
              echo "</div>";
              echo "<div class='col-xs-2'>";
                echo "<center><h5><b>name : </b></h5></center>";
              echo "</div>";
              echo "<div class='col-md-2'>";
                echo "<center><h5>" . $data["name"] . "</h5></center>";
              echo "</div>";
              echo "<div class='col-md-2'>";
                echo "<center><h5><b>username : </b></h5></center>";
              echo "</div>";
              echo "<div class='col-md-1'>";
                echo "<center><h5>" . $data["username"] . "</h5></center>";
              echo "</div>";
              echo "<div class='col-md-2'>";
                echo "<center><h5><a href='profile.php?username=" . $data["username"] . "'>Profile</a></h5></center>";
              echo "</div>";
            echo "</div>";
          echo "</center>";
          echo "<hr>";
        }else if(isset($data['photo_filename'])){
          echo "<center>";
            echo "<div class='row'>";
              echo "<div class='col-md-2'>";
                echo "<center><a href='track.php?id=" . $data["id"] . "'><img src='uploads/tracks/album_cover/" . $data["photo_filename"] . "' alt='profile picture' height=50 width=50></a></center>";
              echo "</div>";
                echo "<div class='col-xs-2'>";
                  echo "<center><h5><b>title : </b></h5></center>";
                echo "</div>";
                echo "<div class='col-md-5'>";
                  echo "<center><h5>" . $data["title"] . "</h5></center>";
                echo "</div>";
                echo "<div class='col-md-2'>";
                  echo "<center><h5><a href='track.php?id=" . $data["id"] . "'>Track</a></h5></center>";
                echo "</div>";
            echo "</div>";
          echo "</center>";
          echo "<hr>";
        }
      }
    }
  } else {
    header("Location: home.php");
  }

  include "footer.php";
