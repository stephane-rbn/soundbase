<?php

  session_start();

  include "includes/head.php";
  require "../conf.inc.php";
  require "../functions.php";

  if (!isConnected() || !isAdmin()) {
    header("Location: ../login.php");
  }

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Users</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Users list
            </div>
            <div class="panel-body">
              <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $connection = connectDB();
                    $sql = $connection->prepare("SELECT username,name,email FROM MEMBER");
                    $sql->execute();
                    $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

                    foreach ($result as $user) {
                      echo '<tr class="odd gradeX">';
                      echo '<td>' . $user['username'];
                      echo '<td>' . $user['name'];
                      echo '<td>' . $user['email'];
                      echo '<td><a href="user_edit.php?email=' . $user['email'] . '">Edit</a>';
                      echo "</tr>";
                    }
                  ?>
                </tbody>
              </table>
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
