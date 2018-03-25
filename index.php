<?php
  // Welcome page (not connected)

  require_once "functions.php";
  include "head.php";
  include "navbar.php";
?>

    <div id="wrapper">
      <h1>REDÉCOUVREZ LA MUSIQUE</h1>
      <h2>MAINTENANT, TOUT DE SUITE</h2>
      <p>
        <a href="register.php"><button class="btn btn-secondary btn-lg">Écouter</button></a>
      </p>
    </div>

    <div class="vertical-spacer"></div>

    <div class="container text-center">
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 feature">
          <img src="vendor/images/welcomepage/icons/home1.png" width="96">
          <p>
            Lorem ipsum dolor sit, amet consectetur adipisicing elit,
            <strong>Reiciendis</strong> fugit.
          </p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 feature">
          <img src="vendor/images/welcomepage/icons/home2.png" width="96">
          <p>
            Lorem ipsum dolor sit, amet consectetur <strong>adipisicing</strong> elit. Ea, id.
          </p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 feature">
          <img src="vendor/images/welcomepage/icons/home3.png" width="96">
          <p>
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sed, <strong>quibusdam</strong> deserunt quidem.
          </p>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 feature">
          <img src="vendor/images/welcomepage/icons/home4.png" width="96">
          <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Accusantium labore amet a itaque <strong>quibusdam</strong> impedit esse.
          </p>
        </div>
      </div>
    </div>

    <div class="vertical-spacer"></div>

    <div class="container">
      <div class="row">
        <div class="col-xs-12 mx-auto">
          <h2 class="text-center mb-3">Inscrivez-vous à la Newsletter</h2>
          <form>
            <div class="row">
              <div class="form-group col-12">
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Entrez votre email">
                <small id="emailHelp" class="form-text text-muted">Nous ne partagerons jamais votre adresse email avec quiconque.</small>
              </div>
            </div>
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-secondary">Soumettre</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="vertical-spacer"></div>

<?php
  include "footer.php";
?>
