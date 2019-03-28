<?php
  require_once "functions.php";

  if (!isset($navbarItem)) {
      $navbarItem = 'home';
  }
?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="vendor/images/logo/complete-white.png" alt="Soundbase logo" width=150>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <form class="form-inline" method="GET" action="searchUsers.php">
            <input class="form-control mr-sm-2" type="search" placeholder="Search on Soundbase " aria-label="Search" name="username">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
          </form>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item<?php if ($navbarItem === 'home') {
    echo ' active';
}?>">
              <a class="nav-link" href="index.php">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <?php if (isConnected()) {
    ?>
              <li class="nav-item<?php if ($navbarItem ==='profile') {
        echo ' active';
    } ?>">
                <a class="nav-link" href="profile.php">Profile</a>
              </li>
              <li class="nav-item<?php if ($navbarItem ==='playlists') {
        echo ' active';
    } ?>">
                <a class="nav-link" href="myPlaylists.php">Playlists</a>
              </li>
              <li class="nav-item<?php if ($navbarItem ==='events') {
        echo ' active';
    } ?>">
                <a class="nav-link" href="events.php">Events</a>
              </li>
            <?php
} ?>
            <li class="nav-item<?php if ($navbarItem === 'charts') {
        echo ' active';
    }?>">
              <a class="nav-link" href="charts.php">Charts</a>
            </li>
            <li class="nav-item<?php if ($navbarItem ==='contact') {
        echo ' active';
    }?>">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
            <?php if (isConnected()) {
        ?>
              <li class="nav-item dropdown <?php if ($navbarItem ==='account') {
            echo ' active';
        } ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Account
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="listeningHistory.php">History</a>
                <a class="dropdown-item" href="recommendations.php">Recommendations</a>
                  <?php
                    if (isAdmin()) {
                        echo '<a class="dropdown-item" href="admin/">Admin</a>';
                    } ?>
                  <a class="dropdown-item" href="edit-profile.php">Edit profile</a>
                  <a class="dropdown-item" href="account.php">Settings</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="logout.php">Logout</a>
                </div>
              </li>
            <?php
    } else {
        ?>
              <li class="nav-item">
                <a class="nav-link" href="register.php">Sign up</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Sign in</a>
              </li>
            <?php
    } ?>
            <?php if (isConnected()) {
        ?>
              <li class="nav-item dropdown <?php if ($navbarItem ==='new') {
            echo ' active';
        } ?>">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-plus"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="newTrackForm.php">New track</a>
                  <a class="dropdown-item" href="newPlaylist.php">New playlist</a>
                  <a class="dropdown-item" href="newEvent.php">New event</a>
                  <a class="dropdown-item" href="newPost.php">New post</a>
                </div>
              </li>
            <?php
    } ?>
            ?>
          </ul>
        </div>
      </div>
    </nav>
