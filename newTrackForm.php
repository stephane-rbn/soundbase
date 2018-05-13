<?php
  session_start();

  require_once "functions.php";

  // redirect to login.php file if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  require_once "conf.inc.php";
  require "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-signup" style="background-image: url('vendor/images/newtrackpage/background.jpg');">
      <h1>NEW TRACK</h1>
      <h2>SHARE YOUR CREATION RIGHT NOW</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="">

        <div class="form-group">
          <label for="title">TITLE</label>
          <input type="text" class="form-control" placeholder="La pluie (feat. Stromae)" name="title" value="" required="required">
        </div>

        <div class="form-group">
          <label for="genre">GENRE</label>
          <select class="form-control" id="genre">
            <option>RnB</option>
            <option>Rock</option>
            <option>Pop</option>
            <option>Hip Hop</option>
            <option>Reggae</option>
          </select>
        </div>

        <div class="form-group">
          <div class="row">
            <label class="col-sm-12">TRACK FILE</label>
          </div>
          <div class="row">
            <input class="col-sm-12" type="file" name="avatar" required/>
          </div>
        </div>

        <div class="form-group">
          <div class="row">
            <label class="col-sm-12">TRACK COVER</label>
          </div>
          <div class="row">
            <input class="col-sm-12" type="file" name="avatar" required/>
          </div>
        </div>

        <div class="form-group">
        <label class="col-sm-12">DESCRIPTION</label>
          <textarea class="form-control" name="" id="" rows="10"></textarea>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Submit</button>
        </div>

      </form>

    </div>
