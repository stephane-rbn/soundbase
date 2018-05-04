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

  // Query that get all data of the member (based on data given in URL)

  if (isset($_GET["username"])) {
    $query = $connection->prepare(
      "SELECT email,name,username,birthday,profile_photo_filename,cover_photo_filename
      FROM MEMBER
      WHERE username='" . $_GET['username'] . "'"
    );
  } else {
    $query = $connection->prepare(
      "SELECT email,name,username,birthday,profile_photo_filename,cover_photo_filename
      FROM MEMBER
      WHERE id=" . $_SESSION['id'] . " AND token='" . $_SESSION['token'] . "'"
    );
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
    <header class="py-5 bg-image-full" style="background-image: url('https://unsplash.it/1900/1080?image=1076');">

      <?php if ($result["profile_photo_filename"] !== "photo.png") { ?>

        <img class="img-fluid d-block mx-auto" src=<?php echo "member/avatar/" . $result["profile_photo_filename"] ?> alt="" width=200 height="200">

      <?php }else{ ?>

        <img class="img-fluid d-block mx-auto" src="http://placehold.it/200x200&text=Logo" alt="">

      <?php }?>

    </header>

    <!-- Content section -->
    <section class="py-5">
      <div class="container">
        <h1><?php echo "Profil de {$result["name"]}"; ?></h1>
        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, suscipit, rerum quos facilis repellat architecto commodi officia atque nemo facere eum non illo voluptatem quae delectus odit vel itaque amet.</p>
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

<?php
  include "footer.php"
?>
