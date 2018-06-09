<?php
  session_start();

  require_once "functions.php";
  require "head.php";
  include "navbar.php";

  // Connection to database
  $connection = connectDB();

  // Query that get all data of the member (based on data given in URL)

  if (isset($_GET["username"])) {
    $query = $connection->prepare(
      "SELECT id,email,name,username,birthday,profile_photo_filename,cover_photo_filename,description
      FROM member
      WHERE username='" . $_GET['username'] . "'"
    );
  } else {
      if (!isConnected()) {
        header("Location: login.php");
      } else {
        $query = $connection->prepare(
          "SELECT id,email,name,username,birthday,profile_photo_filename,cover_photo_filename,description
          FROM member
          WHERE id=" . $_SESSION['id'] . " AND token='" . $_SESSION['token'] . "'"
        );
      }
  }

  // Execute the query
  $query->execute();

  // Fetch data with the query and get it as an associative array
  $result = $query->fetch(PDO::FETCH_ASSOC);

  if (!$result) {
    die("This page doesn't exist");
  }
?>

    <!-- Header - set the background image for the header in the line below -->
    <header class="py-5 bg-image-full" style="background-image: url('<?php
        if ($result["cover_photo_filename"] !== "cover.png") {
          echo "uploads/member/cover/" . $result["cover_photo_filename"];
        } else {
          echo "https://unsplash.it/1900/1080?image=1076";
        }
      ?>');">

      <?php if ($result["profile_photo_filename"] !== "photo.png") { ?>

        <img class="img-fluid d-block mx-auto" src=<?php echo "uploads/member/avatar/" . $result["profile_photo_filename"] ?> alt="" width=200 height="200">

      <?php }else{ ?>

        <img class="img-fluid d-block mx-auto" src="http://placehold.it/200x200&text=Logo" alt="">

      <?php }?>

      <?php
        if (isConnected()) {
          if (!isset($_GET["username"])) {
            echo '<a href="edit-profile.php" class="edit-button cover-buttons" style="color: #d6d6d6" title="Edit cover picture"><i class="fas fa-edit"></i></a>';
            if ($result["cover_photo_filename"] !== 'cover.png') {
              echo '<a href="#" class="delete-button cover-buttons" style="color: #d6d6d6" title="Delete cover picture"><i class="fas fa-trash-alt"></i></a>';
            }
          } else {
            if ($result["id"] === $_SESSION["id"]) {
              echo '<a href="edit-profile.php" class="edit-button cover-buttons" style="color: #d6d6d6" title="Edit cover picture"><i class="fas fa-edit"></i></a>';
              if ($result["cover_photo_filename"] !== 'cover.png') {
                echo '<a href="#" class="delete-button cover-buttons" style="color: #d6d6d6" title="Delete cover picture"><i class="fas fa-trash-alt"></i></a>';
              }
            }
          }
        }
      ?>

    </header>

    <div class="container-fluid"><?php successfulUpdateMessage(); ?></div>

    <!-- Content section -->
    <section class="py-5">
      <div class="container">
        <h1><?php echo "Profil de {$result["name"]}"; ?></h1>
        <p class="lead"><?php
          if (!empty($result["description"]) && $result["description"] !== NULL) {
            echo $result["description"];
          } else {
            echo "No description";
          }
        ?></p>
      </div>
    </section>

    <hr width="500px">

    <!-- Content section -->
    <section class="py-5">
      <div class="container">
        <h1><?php echo "{$result["username"]}'s tracks"; ?></h1>

        <br>
        <?php
          $trackData = sqlSelectFetchAll("SELECT * FROM track WHERE member=" . $result["id"]);
          foreach ($trackData as $track) {
            echo "<center>";
            echo "<h2>" . $track['title'] . "</h2>";
            echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
            echo '<audio controls>';
            echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
            echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
            echo '<hr>';
            echo '</center>';
          }
        ?>
      </div>
    </section>

    <!-- Image Section - set the background image for the header in the line below -->
    <section class="py-5 bg-image-full" style="background-image: url('https://unsplash.it/1900/1080?image=1081');">
      <!-- Put anything you want here! There is just a spacer below for demo purposes! -->
      <div style="height: 200px;"></div>
    </section>

    <!-- Content section -->
    <section class="py-5">
      <div class="container">
        <h1>Section Heading</h1>
        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, suscipit, rerum quos facilis repellat architecto commodi officia atque nemo facere eum non illo voluptatem quae delectus odit vel itaque amet.</p>
      </div>
    </section>

    <?php unset($_SESSION["newTrackAdded"]); ?>

<?php
  include "footer.php"
?>
