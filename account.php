<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  }

  require "head.php";
  include "navbar.php";

  // Connection to database
  $connection = connectDB();

  // Query that get all data of the member
  $query = $connection->prepare(
    "SELECT email,name,username,birthday,profile_photo_filename,cover_photo_filename
    FROM MEMBER
    WHERE id=:toto AND token=:titi"
  );

  // Execute the query
  $query->execute([
    "toto" => $_SESSION["id"],
    "titi" => $_SESSION["token"],
  ]);

  // Fetch data with the query and get it as an associative array
  $result = $query->fetch(PDO::FETCH_ASSOC);

  $_SESSION["resultAccount"] = $result;
?>

    <div class="wrapper" id="wrapper-settings">
      <h1>PARAMÈTRE DU COMPTE</h1>
      <h2>MODIFIEZ OU SUPPRIMEZ VOTRE COMPTE</h2>
    </div>

    <div class="container center_div register-form">

      <form method="POST" action="script/updateUser.php">

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="name">NOM</label>
              <input type="text" class="form-control" name="name" value="<?php echo fillSessionFieldSettings("name"); ?>" required="required">
              <?php
                if (isErrorPresent(1)) {
                  echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
                }
              ?>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="username">USERNAME</label>
              <input type="text" class="form-control" placeholder="orelsan20" name="username" value="<?php echo fillSessionFieldSettings("username"); ?>" required="required">
              <?php
                if (isErrorPresent(2)) {
                  echo '<p class="form_message_error">' . $listOfErrors[2] . '</p>';
                } else if (isErrorPresent(10)) {
                  echo '<p class="form_message_error">' . $listOfErrors[10] . '</p>';
                } else {
                  echo "";
                }
              ?>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="form-group">
              <label for="birthday">DATE DE NAISSANCE</label>
              <input type="date" class="form-control" placeholder="Date d'anniversaire" name="birthday" required="required" value="<?php echo fillSessionFieldSettings("birthday"); ?>">
              <?php
                if (isErrorPresent(3)) {
                  echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
                } else if (isErrorPresent(4)) {
                  echo '<p class="form_message_error">' . $listOfErrors[4] . '</p>';
                } else if (isErrorPresent(5)) {
                  echo '<p class="form_message_error">' . $listOfErrors[5] . '</p>';
                } else {
                  echo '';
                }
              ?>
             </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group">
              <label for="email">ADRESSE EMAIL</label>
              <input type="email" class="form-control" placeholder="orel@san.fr" name="email" value="<?php echo fillSessionFieldSettings("email"); ?>" required="required">
              <?php
                if (isErrorPresent(6)) {
                  echo '<p class="form_message_error">' . $listOfErrors[6] . '</p>';
                } else if (isErrorPresent(7)) {
                  echo '<p class="form_message_error">' . $listOfErrors[7] . '</p>';
                } else {
                  echo '';
                }
              ?>
            </div>
          </div>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-secondary">Mettre à jour</button>
        </div>

      </form>

    </div>

    <?php
      unset($_SESSION["postForm"]);
      unset($_SESSION["errorForm"]);
    ?>

<?php
  include "footer.php"
?>
