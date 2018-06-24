<?php

  session_start();

  require "../functions.php";

  if (!(isConnected() && isAdmin())) {
    header("Location: ../login.php");
  }

  $sql = sqlSelectFetchAll("SELECT COUNT(*) as eventCount FROM events");
  $eventCount = $sql['0']['eventCount'];
  $_SESSION["eventCount"] = $eventCount;

  include "includes/head.php";

?>

<body>
  <div id="wrapper">
    <?php include "includes/nav.php"; ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Events</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              Event list
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
                      <th>Name</th>
                      <th>Description</th>
                      <th>Capacity</th>
                      <th>Event date</th>
                      <th>Member</th>
                      <th>Edit</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $eventCount = $_SESSION['eventCount']; // Number of entries
                      $perPage = 10; // Number of entries per page
                      $nbPages = ceil($eventCount/$perPage); // Number of pages

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

                        $eventData = sqlSelectFetchAll("SELECT * FROM events WHERE (`name` LIKE '%".$searchQuery."%') OR (`description` LIKE '%".$searchQuery."%') LIMIT ". (($cPage - 1) * $perPage) .", $perPage");

                      } else {
                        $eventData = sqlSelectFetchAll("SELECT * FROM events LIMIT ". (($cPage - 1) * $perPage) .", $perPage");
                      }

                      foreach ($eventData as $event) {
                        echo '<tr class="odd gradeX">';
                        echo '<td>' . $event['name'];
                        echo '<td>' . $event['description'];
                        echo '<td>' . $event['capacity'];
                        echo '<td>' . $event['event_date'];
                        $eventCreator = sqlSelect("SELECT name FROM member WHERE id={$event['member']}");
                        echo '<td>' . $eventCreator['name'];
                        echo '<td><a href="event_edit.php?id=' . $event['id'] . '">Edit</a>';
                        echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#exampleModalCenter-{$event['id']}' style='color: #fff'>Delete</button>";
                        echo "</tr>";

                        echo "<!-- Modal {$event['id']}: confirmation of deletion -->";
                        echo "<div class='modal fade' id='exampleModalCenter-{$event['id']}' tabindex='-1' role='dialog' aria-labelledby='exampleModalCenterTitle' aria-hidden='true'>";
                          echo '<div class="modal-dialog modal-dialog-centered" role="document">';
                            echo '<div class="modal-content">';
                              echo '<div class="modal-header">';
                                echo '<h5 class="modal-title" id="exampleModalLongTitle">Are you sure?</h5>';
                                echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                                  echo '<span aria-hidden="true">&times;</span>';
                                echo '</button>';
                              echo '</div>';
                              echo '<div class="modal-body">The deletion of an event is irreversible.</div>';
                              echo '<div class="modal-footer">';
                                echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>';
                                echo '<button type="button" class="btn btn-danger delete-button">';
                                  echo '<a href="script/deleteEvent.php?id=' . $event['id'] .'" style="color: #fff">Confirm</a>';
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
                        $eventCount = $_SESSION['eventCount']; // Number of entries
                        $perPage = 10; // Number of entries per page
                        $nbPages = ceil($eventCount/$perPage); // Number of pages

                        // Page shown defaults to 1, otherwise based on "?page="
                        // Checking $_GET['page'] is a possible page number to provent SQL injections
                        if (isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $nbPages) {
                          $cPage = $_GET['page'];
                        } else {
                          $cPage = 1;
                        }

                        if ($cPage == $nbPages) {
                          // On the last page, the last entry of the page will be the last entry
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".$eventCount." of ".$eventCount." events";
                        } else {
                          echo "Showing ".(($cPage - 1) * $perPage + 1)." to ".(($cPage) * $perPage)." of ".$eventCount." events";
                        }
                      ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">
                      <ul class="pagination">
                        <?php
                          $eventCount = $_SESSION['eventCount']; // Number of entries
                          $perPage = 10; // Number of entries per page
                          $nbPages = ceil($eventCount/$perPage); // Number of pages

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

  unset($_SESSION["eventCount"]);
  unset($_SESSION["sucessDeletion"]);

  include "includes/footer.php";
?>
