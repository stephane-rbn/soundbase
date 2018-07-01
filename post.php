<?php
  session_start();

  require_once "functions.php";

  // redirect to login page if not connected
  if (!isConnected()) {
    header("Location: login.php");
  } else {
    if (!isset($_GET["id"])) {
      die("Error: this track doesn't exist");
    } else {

      // Connection to database
      $connection = connectDB();

      $query = $connection->prepare(
        "SELECT * FROM post WHERE id='" . $_GET['id']. "'"
      );

      $query->execute();

      $post = $query->fetch(PDO::FETCH_ASSOC);
    }

  }

  include "head.php";
  include "navbar.php";

?>

  <body onload="getComments('post',<?php echo $_GET['id'] ?>)">
    <div class="container">

      <div class="vertical-spacer"></div>

    <?php
      echo "<center>";
        echo "<div>";
          echo "<h4>Post :<h4>";
          echo "<h5>" . $post["content"] . "</h5>";
          echo "<p>" . $post["publication_date"] . "</p>";
        echo "</div>";
      echo "</center>";
    ?>

      <div class="vertical-spacer"></div>

      <div class="container center_div register-form">

        <div class="col-sm-12">
        <div class="form-group">
              <label for="content">Comment (280 characters max):</label>
              <textarea name="comment" rows="5" onkeyup="displayStrLength(280);" id="comment-content" class="form-control" placeholder ="Your comment..."></textarea>
              <p id="count"></p>
              <button class="btn btn-secondary" onclick="addComment('post',<?php echo $_GET['id'] ?>)" >Submit</button>
          </div>
        </div>

        <div id="comments">
        </div>
      </div>
    </div>
</body>

<?php
  include "footer.php";
?>
