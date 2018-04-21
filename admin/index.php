<?php

  session_start();

  include "includes/head.php";
  require "../conf.inc.php";
  require "../functions.php";

  if (!isConnected()) {
    header("Location: ../login.php");
  }

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Home</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          Empty for now
        </div>
      </div>
    </div>
  </div>
</body>

<?php
  include "includes/footer.php";
?>
