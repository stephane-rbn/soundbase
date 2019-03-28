<?php

  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
      header("Location: ../login.php");
  }

  $sql = sqlSelectFetchAll("SELECT COUNT(*) as trackCount FROM track");
  $trackCount = $sql['0']['trackCount'];

  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Tracks (under construction)</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Track list
            </div>
            <div class="panel-body">
                <div class="row">
                  <div class="col-sm-6">
                    <form method="GET">
                      <select name="genre" required>
                        <option value="all">All</option>
                        <option value="rb">R&B</option>
                        <option value="rk">Rock</option>
                        <option value="pp">Pop</option>
                        <option value="hh">Hip Hop</option>
                        <option value="rg">Reggae</option>
                        <option value="jz">Jazz</option>
                        <option value="cm">Classical music</option>
                      </select>
                      <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
                  </form>
                  </div>
                  <div class="col-sm-6">
                    <div id="dataTables-example_filter" class="dataTables_filter">
                      <!-- Search form, processed by the same page -->
                      <form method="GET" action="">
                        <input type="search" name="search" class="form-control input-sm" placeholder="Search..." style="max-width: 80%" required>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search fa-fw"></i></button>
                      </form>
                    </div>
                  </div>
                </div>
                <br>
                <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                  <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                      <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Genre</th>
                        <th>Publication Date</th>
                        <th>Member</th>
                        <!-- <th>Edit</th> -->
                        <th>Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $perPage = 10; // Number of entries per page
                        $nbPages = ceil($trackCount/$perPage); // Number of pages

                        // Page shown defaults to 1, otherwise based on "?page="
                        // Checking $_GET['page'] is a possible page number to provent SQL injections
                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                            $cPage = $_GET['page'];
                        } else {
                            $cPage = 1;
                        }

                        if (isset($_GET['search'])) {
                            $_GET['search'] = htmlspecialchars($_GET['search']);

                            $trackData = sqlSelectFetchAll("SELECT * FROM track WHERE `title` LIKE '%" . $_GET['search'] . "%' LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
                        } elseif (isset($_GET['genre'])) {
                            $_GET['genre'] = htmlspecialchars($_GET['genre']);

                            $exist = false;
                            foreach ($listOfGenres as $key => $value) {
                                if ($_GET['genre'] == $key) {
                                    $exist = true;
                                }
                            }

                            // SQL queries searching by genre
                            if ($exist) {
                                $trackData = sqlSelectFetchAll("SELECT * FROM track WHERE genre = '{$_GET['genre']}' LIMIT " . (($cPage - 1) * $perPage) . ", $perPage");
                            } else {
                                $trackData = sqlSelectFetchAll("SELECT * FROM track LIMIT " . (($cPage - 1) * $perPage) . ", $perPage");
                            }
                        } else {
                            $trackData = sqlSelectFetchAll("SELECT * FROM track LIMIT " . (($cPage - 1) * $perPage) . ", $perPage");
                        }

                        foreach ($trackData as $track) {
                            $trackMember = sqlSelect("SELECT name FROM member WHERE id = {$track['member']}");
                            echo '<tr class="odd gradeX">';
                            echo '<td>' . $track['title'];
                            echo '<td>' . $track['description'];
                            echo '<td>' . $listOfGenres[$track['genre']];
                            echo '<td>' . $track['publication_date'];
                            echo '<td>' . $trackMember['name'];
                            // echo '<td><a href="track_edit.php?id=' . $track['id'] . '">Edit</a>';
                            echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#exampleModalCenter-{$track['id']}' style='color: #fff'>Delete</button>";
                            echo "</tr>";
                            echo "<!-- Modal {$track['id']}: confirmation of deletion -->";
                            echo "<div class='modal fade' id='exampleModalCenter-{$track['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
                            echo '<div class="modal-dialog modal-dialog-centered" role="document">';
                            echo '<div class="modal-content">';
                            echo '<div class="modal-header">';
                            echo '<h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>';
                            echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                            echo '<span aria-hidden="true">&times;</span>';
                            echo '</button>';
                            echo '</div>';
                            echo '<div class="modal-body">The deletion of an track is irreversible.</div>';
                            echo '<div class="modal-footer">';
                            echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                            echo '<button type="button" class="btn btn-danger delete-button">';
                            echo '<a href="script/deleteTrack.php?id=' . $track['id'] .'" style="color: #fff">Confirm</a>';
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
                        $perPage = 10; // Number of entries per page
                        $nbPages = ceil($trackCount/$perPage); // Number of pages

                        // Page shown defaults to 1, otherwise based on "?page="
                        // Checking $_GET['page'] is a possible page number to provent SQL injections
                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                            $cPage = $_GET['page'];
                        } else {
                            $cPage = 1;
                        }

                        if ($cPage == $nbPages) {
                            // On the last page, the last entry of the page will be the last entry
                            echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".$trackCount." of ".$trackCount." tracks";
                        } else {
                            echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".(($cPage) * $perPage)." of ".$trackCount." tracks";
                        }
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                      <ul class="pagination">
                        <?php
                          $perPage = 10; // Number of entries per page
                          $nbPages = ceil($trackCount/$perPage); // Number of pages

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

  unset($_SESSION["trackCount"]);
  unset($_SESSION["sucessDeletion"]);

  include "includes/footer.php";
?>
