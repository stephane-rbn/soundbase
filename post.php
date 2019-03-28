<?php
  session_start();

  require_once "functions.php";

  xssProtection();

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

          // Get the artist name
          $authorQuery = $connection->prepare(
        "SELECT name FROM member WHERE id = " . $post['member'] . ""
      );
          $authorQuery->execute();
          $authorResult = $authorQuery->fetch(PDO::FETCH_ASSOC);
          $author = $authorResult['name'];
      }
  }

  include "head.php";
  include "navbar.php";

?>

  <body onload="getComments('post',<?php echo $_GET['id'] ?>)">
    <div class="container">

      <div class="vertical-spacer"></div>
        <div class="row justify-content-center">

          <?php
            echo "<div class='col-lg-7 content-container'>";
            echo "<h3>Post from $author</h3>";
            echo "<p><i class='fas fa-calendar-alt'></i> {$post['publication_date']}</p>";
            echo "<p class='alert alert-secondary'>{$post['content']}</p>";
            echo "</div>";
          ?>

          </div>

      <div class="container center_div register-form">

        <div class="col-sm-12">
          <div class="form-group">
              <label for="content">Comment (280 characters max):</label>
              <textarea name="comment" rows="5" onkeyup="displayTextareaLength(280)" id="comment-content" class="form-control" placeholder ="Your comment..."></textarea>
              <p id="textarea-counter"></p>
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
