<?php
  require "head.php";
  include "navbar.php";
?>

  <div id="wrapper-signup">
    <h1>INSCRIVEZ-VOUS</h1>
    <h2>A LA VITESSE DU SON</h2>

    <div class="container center_div">

      <form method="POST" action="script/saveUser.php">

        <div class="col-md-8 offset-md-2">
          <div class="form-row">
            <div class="form-group col-sm-4">
              <input type="text" class="form-control" placeholder="Prénom" name="firstName" value="" required="required">
            </div>
            <div class="form-group col-sm-4">
              <input type="text" class="form-control" placeholder="Nom" name="lastName" value="" required="required">
            </div>
            <div class="form-group col-sm-4">
              <input type="date" class="form-control" placeholder="Date d'anniversaire" name="birthday" required="required" value="">
            </div>
          </div>
        </div>

        <div class="col-md-8 offset-md-2">
          <div class="form-row">
            <div class="form-group col-sm-6">
              <input type="text" class="form-control" placeholder="Nom d'artiste" name="musicianName" value="" required="required">
            </div>
            <div class="form-group col-sm-6">
              <input type="email" class="form-control" placeholder="Email" name="email" value="" required="required">
            </div>
          </div>
        </div>

        <div class="col-md-8 offset-md-2">
          <div class="form-row">
            <div class="form-group col-sm-6">
              <input type="password" class="form-control" placeholder="Mot de passe" name="pwd" required="required">
            </div>
            <div class="form-group col-sm-6">
              <input type="password" class="form-control" placeholder="Confirmation" name="pwdConfirm" required="required">
            </div>
          </div>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="cgu" required="required">
          <label class="form-check-label" style="margin-bottom:10px;">J'accepte les CGUs de ce site</label>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-bottom:10px;">Submit</button>
      </form>

    </div>

<?php
  include "footer.php";
?>
  </div>
