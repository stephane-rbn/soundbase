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
            <?php echo $_GET['email']; ?>
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
                  <form role="form " method="POST" action="scripts/alter_user.php">
                    <div class="form-group">
                      <label>Email</label>
                      <input class="form-control" placeholder="<?php
                        $connection = connectDB();
                        $sql = $connection->prepare(" SELECT email FROM MEMBER WHERE email='" . $_GET['
                        email '] . "' ");
                        $sql->execute();
                        $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                        echo $result[0]['email'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>First Name</label>
                      <input class="form-control" placeholder="<?php
                        $connection = connectDB();
                        $sql = $connection->prepare(" SELECT first_name FROM MEMBER WHERE email='" . $_GET['email '] . "' ");
                        $sql->execute();
                        $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                        echo $result[0]['first_name'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Last Name</label>
                      <input class="form-control" placeholder="<?php
                        $connection = connectDB();
                        $sql = $connection->prepare(" SELECT last_name FROM MEMBER WHERE email='" . $_GET['
                        email '] . "' ");
                        $sql->execute();
                        $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                        echo $result[0]['last_name'];
                      ?>">
                    </div>
                    <div class="form-group">
                      <label>Musician name</label>
                      <input class="form-control" placeholder="<?php
                        $connection = connectDB();
                        $sql = $connection->prepare(" SELECT musician_name FROM MEMBER WHERE email='" . $_GET['
                        email '] . "' ");
                        $sql->execute();
                        $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                        echo $result[0]['musician_name'];
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
