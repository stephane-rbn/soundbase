<?php
  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../login.php");
  }

  $sql = sqlSelectFetchAll("SELECT COUNT(*) as userCount FROM member");
  $userCount = $sql['0']['userCount'];
  $_SESSION["userCount"] = $userCount;

  include "includes/head.php";


  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <br>
        <?php
          if (isset($_SESSION["message"])) {
            fillAllFieldsErrorMessage();
          } else {
            successfulUpdateMessage();
          }
        ?>
        <div class="col-lg-12">
          <h1 class="page-header">Edit
            <?php
              $result = sqlSelect("SELECT username FROM member WHERE id='".$_GET['id']."'");
              echo $result['username'];
            ?>
          </h1>
          <?php if (isset($_SESSION["errorForm"])) { var_dump($_SESSION["errorForm"]); } ?>
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
                  <form role="form " method="POST" action="script/updateUser.php">
                    <div class="form-group">
                      <label>Username</label>
                      <input class="form-control" name="username" value="<?php
                        if (!isset($_SESSION["postForm"]["username"])) {
                          $result = sqlSelect("SELECT username FROM member WHERE id='".$_GET['id']."' ");
                          echo $result['username'];
                        } else {
                          echo $_SESSION["postForm"]["username"];
                        }
                        ?>" required="required">
                        <?php
                          if (isErrorPresent(2)) {
                            echo '<p class="form_message_error">' . $listOfErrors[2] . '</p>';
                          } else if (isErrorPresent(10)) {
                            echo '<p class="form_message_error">' . $listOfErrors[10] . '</p>';
                          } else {
                            echo "";
                          }
                        ?>
                    </div>
                    <div class="form-group">
                      <label>Name</label>
                      <input class="form-control" name="name" value="<?php
                        if (!isset($_SESSION["postForm"]["name"])) {
                          $result = sqlSelect("SELECT name FROM member WHERE id='".$_GET['id']."' ");
                          echo $result['name'];
                        } else {
                          echo $_SESSION["postForm"]["name"];
                        }
                        ?>" required="required">
                        <?php
                          if (isErrorPresent(1)) {
                            echo '<p class="form_message_error">' . $listOfErrors[1] . '</p>';
                          }
                        ?>
                    </div>
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" class="form-control" name="email" value="<?php
                        if (!isset($_SESSION["postForm"]["email"])) {
                          $result = sqlSelect("SELECT email FROM member WHERE id='".$_GET['id']."' ");
                          echo $result['email'];
                        } else {
                          echo $_SESSION["postForm"]["email"];
                        }
                        ?>" required="required">
                      <?php
                        if (isErrorPresent(6)) {
                          echo '<p class="form_message_error">' . $listOfErrors[6] . '</p>';
                        } else if (isErrorPresent(7)) {
                          echo '<p class="form_message_error">' . $listOfErrors[7] . '</p>';
                        } else {
                          echo '';
                        }
                      ?>
                    </div>
                    <div class="form-group">
                      <label>Birthday</label>
                      <input type="date" class="form-control" name="birthday" value="<?php
                        if (!isset($_SESSION["postForm"]["birthday"])) {
                          $result = sqlSelect("SELECT birthday FROM member WHERE id='".$_GET['id']."' ");
                          echo $result['birthday'];
                        } else {
                          echo $_SESSION["postForm"]["birthday"];
                        }
                        ?>" required="required">
                        <?php
                          if (isErrorPresent(3)) {
                            echo '<p class="form_message_error">' . $listOfErrors[3] . '</p>';
                          } else if (isErrorPresent(4)) {
                            echo '<p class="form_message_error">' . $listOfErrors[4] . '</p>';
                          } else if (isErrorPresent(5)) {
                            echo '<p class="form_message_error">' . $listOfErrors[5] . '</p>';
                          } else {
                            echo '';
                          }
                        ?>
                    </div>
                    <div class="form-group">
                      <label>Position</label>
                      <select class="form-control form-control-sm" name="position" required="required">
                      <?php
                        if (!isset($_SESSION["postForm"]["position"])) {
                          $result = sqlSelect("SELECT position FROM member WHERE id='".$_GET['id']."' ");
                          $status = $result['position'];

                          echo '<option value="0"' . (($status == 0) ? 'selected="selected"' : '') . '>User</option>';
                          echo '<option value="1"' . (($status == 1) ? 'selected="selected"' : '') . '>Admin</option>';
                          echo '<option value="2"' . (($status == 2) ? 'selected="selected"' : '') . '>Banned</option>';
                        } else {
                          echo '<option value="0"' . (($_SESSION["postForm"]["position"] == 0) ? 'selected="selected"' : '') . '>User</option>';
                          echo '<option value="0"' . (($_SESSION["postForm"]["position"] == 1) ? 'selected="selected"' : '') . '>Admin</option>';
                          echo '<option value="0"' . (($_SESSION["postForm"]["position"] == 2) ? 'selected="selected"' : '') . '>Banned</option>';
                        }
                      ?>
                      </select>
                      <?php
                        if (isErrorPresent(24)) {
                          echo '<p class="form_message_error">' . $listOfErrors[24] . '</p>';
                        }
                      ?>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_GET['id'];?>">
                    <button type="submit" class="btn btn-default">Submit</button>
                  </form>
                  <?php
                    unset($_SESSION["postForm"]);
                    unset($_SESSION["errorForm"]);
                    unset($_SESSION["message"]);
                  ?>
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
