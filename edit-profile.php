<?php
  session_start();
  require "functions.php";

  // redirect to home.php file if already connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  require_once "conf.inc.php";
  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-edit-profile">
      <h1>EDIT PROFILE</h1>
      <h2>CHOOSE YOUR STYLE</h2>
    </div>

    <div class="push"></div>

    <div class="container-fluid"><?php loginErrorMessage(); ?></div>

    <div class="container center_div register-form">

      <form action="script/uploadProfilePhotos.php" method="POST" enctype="multipart/form-data">
        <!-- <div class="row">
          <div class="form-group col-sm-12">
            <label>PROFILE PICTURE</label>
            <div class="custom-file">
              <input type="file" name="avatar" />
              <label class="custom-file-label" for="avatar">Choose file (JPG, JPEG, PNG or GIF)</label>
            </div>
          </div>
        </div> -->

        <!-- <div class="row">
          <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-secondary">UPDATE PROFILE PICTURE</button>
          </div>
        </div> -->

        <div class="row">
          <label>File (JPG, JPEG, PNG or GIF ) :</label>
          <input type="file" name="avatar" required/>
        </div>

        <div class="row">
          <div class="form-group col-sm-12">
            <input type="submit" name="submit-avatar" value="UPDATE PROFILE PICTURE" />
          </div>
        </div>

      </form>

      <form action="script/uploadProfilePhotos.php" method="POST" enctype="multipart/form-data">
        <!-- <div class="row">
          <div class="form-group col-sm-12">
            <label>PROFILE PICTURE</label>
            <div class="custom-file">
              <input type="file" name="avatar" />
              <label class="custom-file-label" for="avatar">Choose file (JPG, JPEG, PNG or GIF)</label>
            </div>
          </div>
        </div> -->

        <!-- <div class="row">
          <div class="form-group col-sm-12">
            <button type="submit" class="btn btn-secondary">UPDATE PROFILE PICTURE</button>
          </div>
        </div> -->

        <div class="row">
          <label>File (JPG, JPEG, PNG or GIF ) :</label>
          <input type="file" name="cover" required/>
        </div>

        <div class="row">
          <div class="form-group col-sm-12">
            <input type="submit" name="submit-cover" value="UPDATE PROFILE COVER" />
          </div>
        </div>

      </form>

    </div>

<?php
  include "footer.php";
?>
