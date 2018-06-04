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
              $result = sqlSelect("SELECT username FROM member WHERE id='".$_GET['id']."'");
              echo $result['username'];
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
                        $result = sqlSelect("SELECT username FROM member WHERE id='".$_GET['id']."' ");
                        echo $result['username'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Name</label>
                      <input class="form-control" name="name" value="<?php
                        $result = sqlSelect("SELECT name FROM member WHERE id='".$_GET['id']."' ");
                        echo $result['name'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" value="<?php
                        $result = sqlSelect("SELECT email FROM member WHERE id='".$_GET['id']."' ");
                        echo $result['email'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Birthday</label>
                      <input type="date" class="form-control" name="birthday" value="<?php
                        $result = sqlSelect("SELECT birthday FROM member WHERE id='".$_GET['id']."' ");
                        echo $result['birthday'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Status</label>
                      <select class="form-control form-control-sm">
                      <?php
                        $result = sqlSelect("SELECT position FROM member WHERE id='".$_GET['id']."' ");
                        $status = $result['position'];

                        echo '<option value="0"' . (($status == 1) ? 'selected="selected"' : '') . '>User</option>';
                        echo '<option value="1"' . (($status == 2) ? 'selected="selected"' : '') . '>Admin</option>';
                        echo '<option value="2"' . (($status == 3) ? 'selected="selected"' : '') . '>Banned</option>';
                      ?>
                      </select>
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
