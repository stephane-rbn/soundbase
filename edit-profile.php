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

  $connection = connectDB();

  if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {

    // Fix member profile picture max size to 2MB
    $maxsize = 2097152;
    $validFormats = ['jpg', 'jpeg', 'gif', 'png'];

    if ($_FILES['avatar']['size'] <= $maxsize) {

      $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));

      if (in_array($extensionUpload, $validFormats)) {

        $path = "member/avatar/" . $_SESSION['id'] . "." . $extensionUpload;
        $result = move_uploaded_file($_FILES['avatar']['tmp_name'],$path);

        if ($result) {
          $updateAvatar = $connection->prepare('UPDATE MEMBER SET profile_photo_filename=:profile_photo_filename WHERE id=:id');
          $updateAvatar->execute([
            'profile_photo_filename' => $_SESSION['id'] . "." . $extensionUpload,
            'id'                     => $_SESSION['id']
          ]);

          header('Location: profile.php');
        } else {
          //message d'erreur "Erreur durant l'importation de votre photo de profil"
          echo "Error while importing your file ";
        }
      } else {
        //message d'erreur "Votre photo de profil doit être au format jpg, jpeg, gif ou png"
        echo "Your file must be in the format jpg, jpeg, gif ou png";
      }
    } else {
      //message d'erreur "Votre photo de profil ne doit pas dépasser 10 Mo"
      echo "Your file should not exceed 2Mo";
    }
  }

?>


    <div class="wrapper" id="wrapper-edit-profile">
      <h1>EDIT PROFILE</h1>
      <h2>CHOOSE YOUR STYLE</h2>
    </div>

    <div class="push"></div>

    <div class="container-fluid"><?php loginErrorMessage(); ?></div>

    <div class="container center_div register-form">

      <form method="POST" enctype="multipart/form-data">
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
          <label>Profile picture (JPG, JPEG, PNG or GIF ) :</label>
          <input type="file" name="avatar" />
        </div>

        <div class="row">
          <div class="form-group col-sm-12">
            <input type="submit" name="submit" value="UPDATE PROFILE PICTURE" />
          </div>
        </div>

      </form>

    </div>

<?php
  include "footer.php";
?>
