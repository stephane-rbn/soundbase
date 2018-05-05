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

  if (count($_POST) === 1
    && isset($_POST['submit'])
    && !empty($_FILES['avatar'])
  ) {

    $fileName    = $_FILES['avatar']['name'];
    $fileTmpName = $_FILES['avatar']['tmp_name'];
    $fileSize    = $_FILES['avatar']['size'];
    $fileError   = $_FILES['avatar']['error'];

    // Split the file name into an array by the separating the string into substrings using the '.' character
    $fileNameArray = explode('.', $fileName);

    // Get the last element of the array and make it in lower case
    $fileExtension = strtolower(end($fileNameArray));

    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];

    // Check if the given file owns an allowed extension
    if (in_array($fileExtension, $allowedExtensions)) {

      // Check error at upload
      if ($fileError === 0) {

        // Check if the file size doesn't exceed 2MB
        if ($fileSize < 2097152) {

          $fileNewName = $_SESSION['id'] . "." . $fileExtension;
          $fileDestination = "member/avatar/" . $fileNewName;

          // Move the upload file from its tmp directory to its final destination
          // $result's value is true when the move succeeds
          $result = move_uploaded_file($fileTmpName, $fileDestination);

          if ($result) {
            $query = $connection->prepare('UPDATE MEMBER SET profile_photo_filename=:profile_photo_filename WHERE id=:id');

            $query->execute([
              'profile_photo_filename' => $_SESSION['id'] . "." . $fileExtension,
              'id'                     => $_SESSION['id']
            ]);

            unset($_POST['submit']);

            header('Location: profile.php');
          }

        } else {
          die("Your file is too big!");
        }
      } else {
        die("There was an error uploading your file!");
      }
    } else {
      die("You can not upload files of this type!");
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
          <input type="file" name="avatar" required/>
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
