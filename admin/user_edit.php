<?php
  include "head.php";
  require "../conf.inc.php";
  require "../functions.php";
?>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../admin">Soundbase admin</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-users fa-fw"></i> Users</a>
                        </li>
                        <li>
                            <a href="songs.php"><i class="fa fa-music fa-fw"></i> Songs</a>
                        </li>
                        <li>
                            <a href="envents.php"><i class="fa fa-calendar fa-fw"></i> Events</a>
                        </li>
                        <li>
                            <a href="posts.php"><i class="fa fa-edit fa-fw"></i> Posts</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Edit <?php echo $_GET['email']; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
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
                                    <form role="form">
                                    <div class="form-group">
                                            <label>Email</label>
                                            <input class="form-control" placeholder="<?php
                                              $connection = connectDB();
                                              $sql = $connection->prepare("SELECT email FROM MEMBER WHERE email='" . $_GET['email'] . "'");
                                              $sql->execute();
                                              $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                                              echo $result[0]['email'];
                                            ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" placeholder="<?php
                                              $connection = connectDB();
                                              $sql = $connection->prepare("SELECT first_name FROM MEMBER WHERE email='" . $_GET['email'] . "'");
                                              $sql->execute();
                                              $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                                              echo $result[0]['first_name'];
                                            ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input class="form-control" placeholder="<?php
                                              $connection = connectDB();
                                              $sql = $connection->prepare("SELECT last_name FROM MEMBER WHERE email='" . $_GET['email'] . "'");
                                              $sql->execute();
                                              $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                                              echo $result[0]['last_name'];
                                            ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Musician name</label>
                                            <input class="form-control" placeholder="<?php
                                              $connection = connectDB();
                                              $sql = $connection->prepare("SELECT musician_name FROM MEMBER WHERE email='" . $_GET['email'] . "'");
                                              $sql->execute();
                                              $result = $sql->fetchAll(\PDO::FETCH_ASSOC);
                                              echo $result[0]['musician_name'];
                                            ?>">
                                        </div>
                                        <button type="submit" class="btn btn-default">Submit</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script> 
    <!-- DataTables JavaScript -->
    <script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>
    
    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>

</body>

<?php
  include "footer.php";
?>
