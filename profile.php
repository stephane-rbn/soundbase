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
        <div class="row">
          <div class="col-sm-12 col-md-6">
            <h2><?php echo "{$result["username"]}'s tracks"; ?></h2>

            <br>
            <?php
              $trackData = sqlSelectFetchAll("SELECT * FROM track WHERE member=" . $result["id"]);
              if (count($trackData) !== 0) {

                foreach ($trackData as $track) {
                  $likesQuery = $connection->prepare(
                    "SELECT COUNT(*) as likes FROM likes WHERE track=" . $track['id']
                  );

                  $isLikedQuery = $connection->prepare(
                    "SELECT COUNT(*) as liked FROM likes WHERE track='" . $track['id'] . "' AND member='" . $_SESSION['id'] ."'"
                  );

                  $likesQuery->execute();
                  $isLikedQuery->execute();

                  $likesResult = $likesQuery->fetch(PDO::FETCH_ASSOC);
                  $isLikedResult = $isLikedQuery->fetch(PDO::FETCH_ASSOC);

                  $likes = $likesResult['likes'];
                  $isLiked = $isLikedResult['liked'];

                  echo "<center>";
                  echo "<h3><a href='track.php?id=" . $track['id'] . "'>" . $track['title'] . "</a><a href='' style='color: #c8c8c8;' title='Add to a playlist' data-toggle='modal' data-target='#addToPlaylistModal-{$track['id']}'><i class='fas fa-plus fa-xs' style='margin-left: 10px;'></i></a></h3>";
                  echo '<img src="uploads/tracks/album_cover/'. $track['photo_filename'] . '" height="100px">';
                  echo '<audio controls>';
                  echo '<source src="uploads/tracks/files/' . $track['track_filename'] . '" type="audio/flac">';
                  echo '</audio><br> Artist: ' . $track['member'] . '<br> Genre: ' . $listOfGenres[$track['genre']] . '<br> Publication: ' . $track['publication_date'] . '<br>';
                  echo '<hr>';
                  echo '<span class="likes" onclick="likeTrack('. $track['id'] . ')">';
                  echo '<i class="' . (($isLiked == 1) ? 'fas' : 'far') . ' fa-heart"></i>';
                    echo '<span class="likeNumber" id="likeNumber-' .$track['id'] . '">' .$likes . '</span>';
                  echo '</span>';
                  echo '</center>';
                  echo "<!-- Add to playlist button Modal -->";
                  echo "<div class='modal fade' id='addToPlaylistModal-{$track['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
                    echo "<div class='modal-dialog modal-dialog-centered' role='document'>";
                      echo "<div class='modal-content'>";
                        echo "<div class='modal-header'>";
                          echo "<h5 class='modal-title' id='exampleModalLongTitle'>My playlists</h5>";
                          echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                            echo "<span aria-hidden='true'>&times;</span>";
                          echo "</button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";

                        $getAllPlaylistsQuery = $connection->prepare(
                          "SELECT * FROM playlist WHERE member='" . $_SESSION['id']. "'"
                        );

                        $getAllPlaylistsQuery->execute();

                        $allPlaylists = $getAllPlaylistsQuery->fetchAll(PDO::FETCH_ASSOC);

                        if (count($allPlaylists) === 0) {
                          echo "<h3>No playlist created. <a href='newPlaylist.php'>Create one!</a></h3>";
                        } else {
                          foreach($allPlaylists as $playlist) {
                            echo "<h3><a href='script/addToPlaylist.php?playlist_id=" . $playlist["id"] . "&track_id=" . $track['id'] . "'>" . $playlist["name"] . "</a></h3>";
                            echo "<hr>";
                          }
                        }
                        echo "</div>";
                        echo "<div class='modal-footer'>";
                          echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";
                }
              } else {
                if ($result["id"] === $_SESSION["id"]) {
                  echo "<p>No track uploaded. <a href='newtrackForm.php'>Upload now!</a></p>";
                } else {
                  "<p>No track uploaded.";
                }
              }
            ?>
          </div>

          <div class="col-sm-12 col-md-6">
            <div class="row">
              <div class="col-sm-12">
                <h2><?php echo "{$result["username"]}'s events"; ?></h2>
                <br>
                <?php
                  $events = sqlSelectFetchAll("SELECT * FROM events WHERE member=" . $result["id"]);
                  if (count($events) !== 0) {
                    foreach ($events as $event) {
                      echo "<center>";
                          echo "<h4><a href='event.php?id=" . $event["id"] . "'>" . $event["name"] . "</a></h4>";
                          echo "<p>" . $event["event_date"] . "</p>";
                      echo "</center>";
                    }
                  } else {
                    if ($result["id"] === $_SESSION["id"]) {
                      echo "<p>No event created. <a href='newtrackForm.php'>Upload now!</a></p>";
                    } else {
                      echo "<p>No event created.";
                    }
                  }
                ?>
              </div>
              <div class="col-sm-12">
                <h2><?php echo "{$result["username"]}'s posts"; ?></h2>
              </div>
            </div>
          </div>
        </div>
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
