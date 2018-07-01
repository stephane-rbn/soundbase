<?php
  session_start();
  require_once "functions.php";

  // redirect to home.php file if already connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    $connection = connectDB();

    $query = $connection->prepare(
      "SELECT id,email,name,username,birthday,profile_photo_filename,cover_photo_filename,description FROM member WHERE id=" . $_SESSION['id']
    );

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
  }

  $navbarItem = 'account';
  include "head.php";
  include "navbar.php";
?>

    <div class="wrapper" id="wrapper-edit-profile">
      <h1>EDIT PROFILE</h1>
      <h2>CHOOSE YOUR STYLE</h2>
    </div>

    <div class="container-fluid"><?php successfulUpdateMessage(); ?></div>

    <div class="container center_div register-form">

      <form action="script/uploadProfilePhotos.php" method="POST" enctype="multipart/form-data">

        <div class="row">
          <h3>Profile avatar</h3>
          <br>
          <div>
            <label>File (JPG, JPEG, PNG or GIF) :</label>
            <input type="file" name="avatar" required/><?php
              if (isErrorPresent(13)) {
                echo '<p class="form_message_error">' . $listOfErrors[13] . '</p>';
              } else if (isErrorPresent(14)) {
                echo '<p class="form_message_error">' . $listOfErrors[14] . '</p>';
              } else if (isErrorPresent(15)) {
                echo '<p class="form_message_error">' . $listOfErrors[15] . '</p>';
              } else {
                echo "";
              }
            ?>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-sm-12">
            <br>
            <input type="submit" class="btn btn-secondary" name="submit-avatar" value="UPDATE PROFILE PICTURE" />
          </div>
        </div>

      </form>

      <form action="script/uploadProfilePhotos.php" method="POST" enctype="multipart/form-data">

        <div class="row">
          <h3>Profile cover</h3>
          <br>
          <div>
            <label>File (JPG, JPEG, PNG or GIF) :</label>
            <input type="file" name="cover" required/><?php
              if (isErrorPresent(16)) {
                echo '<p class="form_message_error">' . $listOfErrors[16] . '</p>';
              } else if (isErrorPresent(17)) {
                echo '<p class="form_message_error">' . $listOfErrors[17] . '</p>';
              } else if (isErrorPresent(18)) {
                echo '<p class="form_message_error">' . $listOfErrors[18] . '</p>';
              } else {
                echo "";
              }
            ?>
          </div>
        </div>

        <div class="row">
          <div class="form-group col-sm-12">
            <br>
            <input type="submit" class="btn btn-secondary" name="submit-cover" value="UPDATE PROFILE COVER" />
          </div>
        </div>

      </form>

      <form action="script/uploadDescription.php" method="POST" enctype="multipart/form-data">

        <div class="row">
          <h3>Description (2500 characters max):</h3>
          <textarea name="description" onkeyup="displayTextareaLength(2500);" id="textarea" class="form-control" rows="10" placeholder ="Your description .."><?php
            if(!empty($result["description"]) && $result["description"] !== NULL && !isErrorPresent(12)) {
              echo $result["description"];
            }
            if(isErrorPresent(12)) {
              echo fillSessionFieldSettings("description");
            } ?></textarea>
          <p id="textarea-counter"></p>
          <?php
              if (isErrorPresent(12)) {
              echo '<p class="form_message_error">' . $listOfErrors[12] . ' (2500)</p>';
              }
            ?>
        </div>

        <input type="hidden" name="maxLength" value="2500">

        <div class="row">
          <div class="form-group col-sm-12">
            <br>
            <input type="submit" class="btn btn-secondary" name="submit-description" value="UPDATE PROFILE DESCRIPTION" />
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
