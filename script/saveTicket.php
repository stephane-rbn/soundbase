<?php
  session_start();

  include "../functions.php";

  $memberData = sqlSelect('SELECT username FROM member WHERE id=' . $_SESSION['id']);

  $eventData = sqlSelect('SELECT registration_token FROM registration WHERE member=' . $_SESSION['id'] . ' AND events=' . $_GET['id']);

  $url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={"username":"' . $memberData["username"] . '","registration_token":"' . $eventData["registration_token"] . '"}';
