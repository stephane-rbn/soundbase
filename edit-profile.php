<?php
  session_start();
  require "functions.php";
  require_once "conf.inc.php";

  // redirect to home.php file if already connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    $connection = connectDB();

    $query = $connection->prepare(
      "SELECT id,email,name,username,birthday,profile_photo_filename,cover_photo_filename,description FROM MEMBER WHERE id=" . $_SESSION['id']
    );

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
  }

  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-edit-profile">
      <h1>EDIT PROFILE</h1>
      <h2>CHOOSE YOUR STYLE</h2>
    </div>

    <div class="container-fluid"><?php
      if (isset($_SESSION["message"])) {
        fillAllFieldsErrorMessage();
      } else {
        successfulUpdateMessage();
      } ?>
    </div>

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

      <form action="script/uploadDescription.php" method="POST" enctype="multipart/form-data">

        <div class="row">
          <label>Description (2500 caractères maximum):</label>
          <textarea name="description" onkeyup="displayStrLength(2500);" id="textarea" class="form-control" rows="10" placeholder ="Your description .."><?php
            if (!empty($result["description"]) && $result["description"] !== NULL) {
              echo $result["description"];
            } ?></textarea>
          <p id="count"></p>
        </div>

        <input type="hidden" name="maxLength" value="2500">

        <div class="row">
          <div class="form-group col-sm-12">
            <input type="submit" name="submit-description" value="UPDATE PROFILE DESCRIPTION" />
          </div>
        </div>

      </form>

      <?php
        unset($_SESSION["postForm"]);
        unset($_SESSION["errorForm"]);
        unset($_SESSION["successUpdate"]);
      ?>

    </div>

<?php
  include "footer.php";
?>
