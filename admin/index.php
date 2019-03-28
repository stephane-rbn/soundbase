<?php

  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
      header("Location: ../login.php");
  }

  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Welcome to the administration panel!</h1>
        </div>
      </div>
    </div>
  </div>
</body>

<?php
  include "includes/footer.php";
?>
