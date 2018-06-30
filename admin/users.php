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
              <div class="row">
                <div class="col-sm-6">
                  <form method="GET">
                    <select name="position">
                      <option value="all">All</option>
                      <option value="0">Member</option>
                      <option value="1">Admin</option>
                      <option value="2">Banned</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
                  </form>
                </div>
                <div class="col-sm-6">
                  <div id="dataTables-example_filter" class="dataTables_filter">
                    <!-- Search form, processed by the same page -->
                    <form method="GET" action="">
                      <input type="search" name="search" class="form-control input-sm" placeholder="Search for a specific user..." style="max-width: 80%" required>
                      <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
                    </form>
                  </div>
                </div>
              </div>
              <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                  <div class="col-sm-12">
                  </div>
                </div>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Birthday</th>
                      <th>Position</th>
                      <th>Registration Date</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $userCount = $_SESSION['userCount']; // Number of entries
                      $perPage = 10; // Number of entries per page
                      $nbPages = ceil($userCount/$perPage); // Number of pages

                      // Page shown defaults to 1, otherwise based on "?page="
                      // Checking $_GET['page'] is a possible page number to provent SQL injections
                      if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                        $cPage = $_GET['page'];
                      } else {
                        $cPage = 1;
                      }

                      if (isset($_GET['search'])) {
                        $searchQuery = $_GET['search'];
                        $searchQuery = htmlspecialchars($searchQuery);

                        // SQL query executed when someone is searched
                        $userData = sqlSelectFetchAll("SELECT * FROM member WHERE (`username` LIKE '%".$searchQuery."%') OR (`name` LIKE '%".$searchQuery."%') OR (`email` LIKE '%".$searchQuery."%') LIMIT ". (($cPage - 1) * $perPage) .", $perPage");

                      } else if (isset($_GET['position'])) {
                        $searchQuery = $_GET['position'];
                        $searchQuery = htmlspecialchars($searchQuery);

                        // SQL queries searching by position
                        if (is_numeric($_GET['position'])) {
                          $userData = sqlSelectFetchAll("SELECT * FROM member WHERE position = {$_GET['position']} LIMIT " . (($cPage - 1) * $perPage) . ", $perPage");
                        } else {
                          $userData = sqlSelectFetchAll("SELECT * FROM member LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
                        }

                      } else {
                        $userData = sqlSelectFetchAll("SELECT * FROM member LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
                      }

                      foreach ($userData as $user) {
                        echo '<tr class="odd gradeX">';
                        echo '<td>' . $user['username'];
                        echo '<td>' . $user['name'];
                        echo '<td>' . $user['email'];
                        echo '<td>' . $user['birthday'];
                        if ($user['position'] == 0) {
                          echo "<td> User";
                        } elseif ($user['position'] == 1) {
                          echo "<td> Admin";
                        } elseif ($user['position'] == 2) {
                          echo "<td> Banned";
                        }
                        echo '<td>' . $user['registration_date'];
                        echo '<td><a href="user_edit.php?id=' . $user['id'] . '">Edit</a>';
                        echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#exampleModalCenter-{$user['id']}' style='color: #fff'>Delete</button>";
                        echo "</tr>";
                        echo "<!-- Modal {$user['id']}: confirmation of deletion -->";
                        echo "<div class='modal fade' id='exampleModalCenter-{$user['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
                          echo '<div class="modal-dialog modal-dialog-centered" role="document">';
                            echo '<div class="modal-content">';
                              echo '<div class="modal-header">';
                                echo '<h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>';
                                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                                  echo '<span aria-hidden="true">&times;</span>';
                                echo '</button>';
                              echo '</div>';
                              echo '<div class="modal-body">The deletion of an account is irreversible.</div>';
                              echo '<div class="modal-footer">';
                                echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                                echo '<button type="button" class="btn btn-danger delete-button">';
                                  echo '<a href="script/deleteUser.php?id=' . $user['id'] .'" style="color: #fff">Confirm</a>';
                                echo '</button>';
                              echo '</div>';
                            echo '</div>';
                          echo '</div>';
                        echo '</div>';
                      }
                    ?>
                  </tbody>
                </table>


                <div class="row">
                  <div class="col-sm-6">
                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">
                      <?php
                        $userCount = $_SESSION['userCount']; // Number of entries
                        $perPage = 10; // Number of entries per page
                        $nbPages = ceil($userCount/$perPage); // Number of pages

                        // Page shown defaults to 1, otherwise based on "?page="
                        // Checking $_GET['page'] is a possible page number to provent SQL injections
                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                          $cPage = $_GET['page'];
                        } else {
                          $cPage = 1;
                        }

                        if ($cPage == $nbPages) {
                          // On the last page, the last entry of the page will be the last entry
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".$userCount." of ".$userCount." users";
                        } else {
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".(($cPage) * $perPage)." of ".$userCount." users";
                        }
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                      <ul class="pagination">
                        <?php
                          $userCount = $_SESSION['userCount']; // Number of entries
                          $perPage = 10; // Number of entries per page
                          $nbPages = ceil($userCount/$perPage); // Number of pages

                          // Page shown defaults to 1, otherwise based on "?page="
                          // Checking $_GET['page'] is a possible page number to provent SQL injections
                          if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                            $cPage = $_GET['page'];
                          } else {
                            $cPage = 1;
                          }

                          if ($cPage == 1) {
                            // Disable previous button if first page
                            echo '<li class="paginate_button previous disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a>Previous</a></li>';
                          } else {
                            echo '<li class="paginate_button previous" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_previous"><a href="?page='.($cPage - 1).'">Previous</a></li>';
                          }

                          for ($i = 1; $i <= $nbPages; $i++) {
                            if ($i == $cPage) {
                              // Set the current page number as active
                              echo '<li class="paginate_button active" aria-controls="dataTables-example" tabindex="0"><a href="?page='.$i.'">'.$i.'</a></li>';
                            } else {
                              echo '<li class="paginate_button" aria-controls="dataTables-example" tabindex="0"><a href="?page='.$i.'">'.$i.'</a></li>';
                            }
                          }

                          if ($cPage == $nbPages) {
                            // Disable next button if last page
                            echo '<li class="paginate_button next disabled" aria-controls="dataTables-example" tabindex="0" id="dataTables-example_next"><a>Next</a></li>';
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

  unset($_SESSION["userCount"]);
  unset($_SESSION["sucessDeletion"]);

  include "includes/footer.php";
?>
