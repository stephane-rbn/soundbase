<?php

  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../login.php");
  }

  $sql = sqlSelectFetchAll("SELECT COUNT(*) as postCount FROM post");
  $postCount = $sql['0']['postCount'];
  $_SESSION["postCount"] = $postCount;

  include "includes/head.php";
?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Posts (under construction)</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Posts list
            </div>
            <div class="panel-body">
              <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                  <div class="col-sm-12">
                    <div id="dataTables-example_filter" class="dataTables_filter">
                      <!-- Search form, processed by the same page -->
                      <form method="GET" action="">
                        <input type="search" name="search" class="form-control input-sm" placeholder="Search...">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
                <br>
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Content</th>
                      <th>Publication date</th>
                      <th>Member</th>
                      <th>Edit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $postCount = $_SESSION['postCount']; // Number of entries
                      $perPage = 10; // Number of entries per page
                      $nbPages = ceil($postCount/$perPage); // Number of pages

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

                        $postData = sqlSelectFetchAll("SELECT * FROM post WHERE (`content` LIKE '%".$searchQuery."%') OR (`id` LIKE '%".$searchQuery."%') OR (`publication_date` LIKE '%".$searchQuery."%') LIMIT ". (($cPage - 1) * $perPage) .", $perPage");

                      } else {
                        $postData = sqlSelectFetchAll("SELECT * FROM post LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
                      }

                      foreach ($postData as $post) {
                        echo '<tr class="odd gradeX">';
                        echo '<td>' . $post['id'];
                        echo '<td>' . $post['content'];
                        echo '<td>' . $post['publication_date'];
                        echo '<td>' . $post['member'];
                        echo '<td><a href="post_edit.php?id=' . $post['id'] . '">Edit</a>';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="dataTables_info" id="dataTables-example_info" role="status" aria-live="polite">
                      <?php
                        $postCount = $_SESSION['postCount']; // Number of entries
                        $perPage = 10; // Number of entries per page
                        $nbPages = ceil($postCount/$perPage); // Number of pages

                        // Page shown defaults to 1, otherwise based on "?page="
                        // Checking $_GET['page'] is a possible page number to provent SQL injections
                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                          $cPage = $_GET['page'];
                        } else {
                          $cPage = 1;
                        }

                        if ($cPage == $nbPages) {
                          // On the last page, the last entry of the page will be the last entry
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".$postCount." of ".$postCount." posts";
                        } else {
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".(($cPage) * $perPage)." of ".$postCount." posts";
                        }
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                      <ul class="pagination">
                        <?php
                          $postCount = $_SESSION['postCount']; // Number of entries
                          $perPage = 10; // Number of entries per page
                          $nbPages = ceil($postCount/$perPage); // Number of pages

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

  unset($_SESSION["postCount"]);

  include "includes/footer.php";
?>
