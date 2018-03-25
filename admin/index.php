<?php
  include "header.php";
  require "../conf.inc.php";
  require "../functions.php";
?>

<body>

    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="../admin">
                        Soundbase Admin
                    </a>
                </li>
                <li>
                    <a href="../admin">Dashboard</a>
                </li>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h1>Administration</h1>
                <h3>Liste des utilisateurs</h3>
                <br>

                <table border="1" >
                    <tr>
                        <th> First name
                        <th> Last name
                        <th> Username
                        <th> Email
                    </tr>

                    <?php
                        $connection = connectDB();
                        $sql = $connection->prepare("SELECT * FROM MEMBER");
                        $sql->execute();
                        $result = $sql->fetchAll(\PDO::FETCH_ASSOC);

                        $i = 0;
                        while ($result[$i] !== NULL){
                            echo "<tr>";
                            echo '<td>' . $result[$i]['first_name'];
                            echo '<td>' . $result[$i]['last_name'];
                            echo '<td>' . $result[$i]['musician_name'];
                            echo '<td>' . $result[$i]['email'];
                            echo "</tr>";
                            $i++;
                        }
                    ?>
                </table>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

</body>

<?php
  include "footer.php";
?>
