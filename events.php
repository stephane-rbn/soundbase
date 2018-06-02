<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  include "head.php";
  include "navbar.php";
?>

    <div class="container">

      <div class="vertical-spacer"></div>

      <h1 class="text-center">All events</h1>

      <div style="height: 2em;"></div>

      <div class="row">

        <div class="col-sm-12 col-md-4" style="margin-bottom: 2em;">
          <div class="card" style="width: 100%;">
            <img class="card-img-top" src="http://via.placeholder.com/400x250" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-4" style="margin-bottom: 2em;">
          <div class="card" style="width: 100%;">
            <img class="card-img-top" src="http://via.placeholder.com/400x250" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-md-4" style="margin-bottom: 2em;">
          <div class="card" style="width: 100%;">
            <img class="card-img-top" src="http://via.placeholder.com/400x250" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Card title</h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
              <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
          </div>
        </div>

      </div>

      <div class="vertical-spacer"></div>

    </div>



<?php
  include "footer.php";
?>
