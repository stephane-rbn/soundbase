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
              <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                  <div class="col-sm-12">
                    <div id="dataTables-example_filter" class="dataTables_filter">
                      <label>Search:<input type="search" class="form-control input-sm" placeholder="" aria-controls="dataTables-example"></label>
                    </div>
                  </div>
                </div>
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
                      $sql = $connection->prepare("SELECT COUNT(*) FROM MEMBER");
                      $sql->execute();
                      $result = $sql->fetchAll();

                      $entries = $result['0']['0'];

                      $perPage = 10;
                      $nbPages = ceil($entries/$perPage);

                      if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                        $cPage = $_GET['page'];
                      } else {
                        $cPage = 1;
                      }

                      $connection = connectDB();
                      $sql = $connection->prepare("SELECT username,name,email FROM MEMBER LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
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
                <div class="row">
                  <div class="col-sm-6">
                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">
                      <?php
                      $connection = connectDB();
                      $sql = $connection->prepare("SELECT COUNT(*) FROM MEMBER");
                      $sql->execute();
                      $result = $sql->fetchAll();

                      // Yep it's an array...
                      $entries = $result['0']['0'];

                      $perPage = 3;
                      $nbPages = ceil($entries/$perPage);

                      if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                        $cPage = $_GET['page'];
                      } else {
                        $cPage = 1;
                      }

                      echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".(($cPage) * $perPage)." of " . $entries . " entries";
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                      <ul class="pagination">
                        <?php
                        $connection = connectDB();
                        $sql = $connection->prepare("SELECT COUNT(*) FROM MEMBER");
                        $sql->execute();
                        $result = $sql->fetchAll();

                        // Yep it's an array...
                        $entries = $result['0']['0'];

                        $perPage = 3;
                        $nbPages = ceil($entries/$perPage);

                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                          $cPage = $_GET['page'];
                        } else {
                          $cPage = 1;
                        }

                        if ($cPage == 1) {
                          echo '<li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a>Previous</a></li>';
                        } else {
                          echo '<li class="paginate_button previous" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a href="?page='.($cPage - 1).'">Previous</a></li>';
                        }

                        for ($i=1;$i<=$nbPages;$i++) {
                          // ternary to do here
                          if ($i == $cPage) {
                            echo '<li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="?page='.$i.'">'.$i.'</a></li>'; // <a> to remove
                          } else {
                            echo '<li class="paginate_button" aria-controls="dataTables-example" tabindex="0"><a href="?page='.$i.'">'.$i.'</a></li>';
                          }
                        }

                        if ($cPage == $nbPages) {
                          echo '<li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a>Next</a></li>';
                        } else {
                          echo '<li class="paginate_button next" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a href="?page='.($cPage + 1).'">Next</a></li>';
                        }

                        ?>
                      </ul>
                    </div>
                  </div>
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
