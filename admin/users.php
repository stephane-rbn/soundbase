<?php
  include "includes/head.php";
  require "../conf.inc.php";
  require "../functions.php";
?>

<body>
    <div id="wrapper">
        <?php include "includes/nav.php"; ?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Users</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Users list
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Musician name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $connection = connectDB();
                                    $sql = $connection->prepare("SELECT * FROM MEMBER");
                                    $sql->execute();
                                    $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

                                    $i = 0;
                                    while ($result[$i] !== NULL){
                                        echo '<tr class="odd gradeX">';
                                        echo '<td>' . $result[$i]['first_name'];
                                        echo '<td>' . $result[$i]['last_name'];
                                        echo '<td>' . $result[$i]['musician_name'];
                                        echo '<td>' . $result[$i]['email'];
                                        echo '<td><a href="user_edit.php?email=' . $result[$i]['email'] . '">Edit</a>';
                                        echo "</tr>";
                                        $i++;
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
  include "footer.php";
?>
