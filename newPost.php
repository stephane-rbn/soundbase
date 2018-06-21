<?php
  session_start();

  require_once "functions.php";
  require_once "conf.inc.php";

  // redirect to home.php file if already connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    $connection = connectDB();

    $query = $connection->prepare(
      "SELECT id,post ,member FROM post WHERE member=" . $_SESSION['id']
    );

    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);
  }

  require "head.php";
  include "navbar.php";
?>

  <div class="wrapper" id="wrapper-signup" style="background-image: url('vendor/images/newpostpage/background.jpg');">
    <h1>NEW POST</h1>
    <h2>ANNOUNCEMENT</h2>
  </div>

  <div class="container center_div register-form">
    <form method="POST" action="script/savePost.php">
      <div class="col-sm-12">
        <div class="form-group">
            <label for="content">POST (280 characters max):</label>
            <br>
            <textarea name="post" cols="70" rows="10" onkeyup="displayStrLength(280);" id="textarea" class="form-control" placeholder ="Your publication ..."><?php
            if (!empty($result["post"]) && $result["post"] !== NULL && !isErrorPresent(12)) {
              echo $result["post"];
            }
            if (isErrorPresent(12)) {
              echo fillSessionFieldSettings("post");
            } ?></textarea>
            <p id="count"></p>
            <?php
              if (isErrorPresent(12)) {
              echo '<p class="form_message_error">' . $listOfErrors[12] . ' (280)</p>';
              }
            ?>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-secondary">Submit</button>
      </div>
    </form>
  </div>

<?php

  unset($_SESSION["postForm"]);
  unset($_SESSION["errorForm"]);

  include "footer.php";
?>
