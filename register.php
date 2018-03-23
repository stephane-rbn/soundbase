<?php
  require "head.php";
  include "navbar.php";
  // email
  // firstName
  // lastName
  // birthday
?>

  <div id="wrapper-signup">
    <h1>INSCRIVEZ-VOUS</h1>
    <h2>A LA VITESSE DU SON</h2>

    <div class="container center_div">

      <form>

        <div class="col-md-8 offset-md-2">
          <div class="form-row">
            <div class="form-group col-sm-6">
              <input type="text" class="form-control" placeholder="Prénom" name="firstName" value="" required="required">
            </div>
            <div class="form-group col-sm-6">
              <input type="text" class="form-control" placeholder="Nom" name="lastName" value="" required="required">
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
              <input type="password" class="form-control" id="emailLogin" aria-describedby="emailHelp" placeholder="Mot de passe" name="pwd" required="required">
            </div>
            <div class="form-group col-sm-6">
              <input type="password" class="form-control" id="emailLogin" aria-describedby="emailHelp" placeholder="Confirmation" name="pwdConfirm" required="required">
            </div>
          </div>
        </div>

        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="exampleCheck1">
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

    </div>
  </div>

<?php
  include "footer.php";
?>
