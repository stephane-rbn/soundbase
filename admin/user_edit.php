<?php

  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header"> Edit
            <?php
              $result = sqlSelect("SELECT username FROM MEMBER WHERE id='".$_GET['id']."'");
              echo $result[0]['username'];
            ?>
          </h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Edit user
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-6">
                  <form role="form " method="POST" action="scripts/updateUser.php">
                    <div class="form-group">
                      <label>Username</label>
                      <input class="form-control" name="username" value="<?php
                        $result = sqlSelect("SELECT username FROM MEMBER WHERE id='".$_GET['id']."' ");
                        echo $result[0]['username'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Name</label>
                      <input class="form-control" name="name" value="<?php
                        $result = sqlSelect("SELECT name FROM MEMBER WHERE id='".$_GET['id']."' ");
                        echo $result[0]['name'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input class="form-control" name="email" value="<?php
                        $result = sqlSelect("SELECT email FROM MEMBER WHERE id='".$_GET['id']."' ");
                        echo $result[0]['email'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Birthday</label>
                      <input class="form-control" name="birthday" value="<?php
                        $result = sqlSelect("SELECT birthday FROM MEMBER WHERE id='".$_GET['id']."' ");
                        echo $result[0]['birthday'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <input class="form-control" name="position" value="<?php
                        $result = sqlSelect("SELECT position FROM MEMBER WHERE id='".$_GET['id']."' ");
                        echo $result[0]['position'];
                      ?>">
                    </div>
                    <button type="submit" class="btn btn-default">Submit</button>
                    <button type="reset" class="btn btn-default">Reset</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

<?php
  include "includes/footer.php";
?>
