<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}
$error = "";
$msg = "";

$t_id = $_SESSION['id'];

// Fetch all tables from the database
$tables_result = $con->query("SHOW TABLES");
$tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $table_name = $row[0];
        // Skip tables that start with admin, students, subjects, or teacher
        if (preg_match('/^(admin|students|subjects|teacher)/i', $table_name) === 0) {
            // Check if table has t_id column and if it matches the session ID
            $check_query = $con->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = 't_id'");
            $column_exists = $check_query->fetch_assoc()['count'] > 0;

            if ($column_exists) {
                $sql = "SELECT COUNT(*) AS count FROM " . htmlspecialchars($table_name) . " WHERE t_id = '$t_id'";
                $query = $con->query($sql);
                $result = $query->fetch_assoc();
                $count = $result['count'];

                if ($count > 0) {
                    $tables[] = $table_name;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="text/javascript">
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
</head>

<body>
    <!-- Side-Nav -->
    <?php include("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <div class="card shadow card-body">
                <h2>Existing Tables</h2>
                <div class="row">
                    <?php if (count($tables) > 0): ?>
                        <?php foreach ($tables as $table): ?>
                            <?php
                            
                            $sql = "SELECT COUNT(*) AS count FROM " . htmlspecialchars($table) . " WHERE t_id = '$t_id'";
                            $query = $con->query($sql);
                            $result = $query->fetch_assoc();
                            $count = $result['count'];
                            ?>
                            <div  class="col-xl-3 col-sm-6 col-lg-3" style="margin-top: 20px;">
                                <div class="card" style=" background-color: ;">
                                    <div class="card-body">
                                        <div class="dash-widget-header ">
                                            <span class="dash-widget-icon">
                                                <i class="fa-solid fa-database" style='font-size:20px;'></i>
                                            </span>
                                        </div>
                                        <div class="dash-widget-info">
                                            <h3><?php echo $count; ?></h3>
                                            <h6 class="text-"><?php echo htmlspecialchars($table); ?></h6>
                                           
                                            <a href="dashboard_table_view.php?table=<?php echo urlencode($table); ?>"
                                            class="btn btn-primary item-center">View</a>
                                             <a href="print_data.php?table=<?php echo urlencode($table); ?>"
                                            class="btn btn-dark item-center">Print</a>
                                            <br><br>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-secondary w-50"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No tables found in the database.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
   

    <!-- bootstrap js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>
