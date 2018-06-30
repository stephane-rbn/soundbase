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

  $postID = $_GET['id'];
?>

  <body onload="getComments( null, null, <?php echo $postID ?>)">
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
              <label for="content">POST (280 characters max):</label>
              <textarea name="comment" rows="5" onkeyup="displayStrLength(280);" id="textarea" class="form-control" placeholder ="Your publication ..."></textarea>
              <p id="count"></p>
              <button class="btn btn-secondary" onclick="addComment( null, document.getElementById('textarea').value, null, '<?php echo $_GET["id"]; ?>')" >Submit</button>
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
