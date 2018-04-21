<?php
  require_once "functions.php";
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
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="index.php">Home
                <span class="sr-only">(current)</span>
              </a>
            </li>
            <?php if (isConnected()) { ?>
              <li class="nav-item">
                <a class="nav-link" href="profile.php">Profil</a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
            </li>
            <?php if (isConnected()) { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Account
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="account.php">Votre compte</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">Se déconnecter</a>
              </div>
            </li>
            <?php } else { ?>
              <li class="nav-item">
                <a class="nav-link" href="register.php">S'inscrire</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="login.php">Se connecter</a>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </nav>
