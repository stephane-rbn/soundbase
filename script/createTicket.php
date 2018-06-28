<?php
  session_start();

  include "../functions.php";

  // Includes autoloader
  require_once "../vendor/dompdf/autoload.inc.php";

  $memberData = sqlSelect('SELECT username,name FROM member WHERE id=' . $_SESSION['id']);

  $registrationData = sqlSelect('SELECT registration_token FROM registration WHERE member=' . $_SESSION['id'] . ' AND events=' . $_GET['id']);

  $eventData = sqlSelect('SELECT name, description, event_date, address FROM events WHERE id=' . $_GET['id']);

  $url = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={"username":"' . $memberData["username"] . '","registration_token":"' . $registrationData["registration_token"] . '"}';

  // References the Dompdf namespace
  use Dompdf\Dompdf;

  // New instance of Dompdf class in order to use Dompdf functions
  // By setting 'enable_remote' in true it allows us to use non-local images
  $dompdf = new Dompdf(['enable_remote' => true]);

  // Loads an HTML file
  $dompdf->loadHtml(
    '<!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Your ticket</title>
      </head>
      <body>
        <center>
          <h1>' . $memberData["name"] . '</h1>
          <h2>' . $memberData["username"] . '</h2>
          <img src="' . $url . '">
          <h3>Event Name</h3>
          <p>' . $eventData['name'] . '</p>
          <h3>Event description</h3>
          <p>' . $eventData['description'] . '</p>
          <h3>Date</h3>
          <p>' . $eventData['event_date'] . '</p>
          <h3>Location</h3>
          <p>' . $eventData['address'] . '</p>
          <br>
          <br>
          <br>
          <br>
          <br>
          <footer>
            <img src="../vendor/images/logo/complete-black.png" width="200">
          </footer>
        </center>
      </body>'
  );

  // Sets the paper size & orientation
  $dompdf->setPaper('A4', 'portrait');

  // Render the HTML as PDF
  $dompdf->render();

  // Streams the PDF to the client. The file will open a download dialog by default
  $dompdf->stream($memberData["name"] . "-ticket-" . $eventData["name"]);
