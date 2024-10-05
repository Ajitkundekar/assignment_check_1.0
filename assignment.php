<?php
session_start();
require("config.php");

if (!isset($_SESSION['id'])) {
    header("location:index.php");
    exit();
}

$error = "";
$msg = "";

$t_id = $_SESSION['id'];

// Initialize filter variables and convert to lowercase
$selectedClass = isset($_POST['class']) ? strtolower($_POST['class']) : '';
$selectedBatch = isset($_POST['Batch']) ? strtolower($_POST['Batch']) : '';
$selectedAcademic = isset($_POST['Acadamic']) ? strtolower($_POST['Acadamic']) : '';

// Concatenate form values and replace spaces with underscores
$table_name1 = str_replace(' ', '_', trim($selectedBatch . ' ' . $selectedAcademic));
$table_name = str_replace(' ', '', trim($selectedClass . ' ' . $table_name1));

// Fetch all tables from the database
$tables_result = $con->query("SHOW TABLES");
$tables = [];
$found_table = false;

if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $current_table = $row[0];
        if ($current_table === $table_name) {
            $found_table = true;
            break;
        }
    }
}

if ($found_table) {
    // If table exists, display its name in a card
    $tables[] = $table_name;
} else {
    $error = "Table '$table_name' does not exist.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assignment</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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

            <div class="card card-body shadow">
                <div>
                    <h3 class="page-title"> Search Batch</h3>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="container">
                            <div class="row">

                                <div class="col-md-1 my-auto text-center">
                                    <h5> Class:</h5>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control form-control-lg" aria-label=".form-select-lg example" name="class">
                                        <option selected hidden>--Select Class-- </option>
                                        <?php
                                        $query1 = mysqli_query($con, "SELECT DISTINCT class FROM subjects");
                                        while ($row1 = mysqli_fetch_assoc($query1)) {
                                            ?>
                                            <option value="<?php echo htmlspecialchars($row1['class']); ?>" <?php echo $selectedClass == $row1['class'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($row1['class']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-1 my-auto text-center">
                                    <h5> Batch:</h5>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control form-control-lg" aria-label=".form-select-lg example" name="Batch">
                                        <option selected hidden>--Select Batch-- </option>
                                        <?php
                                        $query1 = mysqli_query($con, "SELECT DISTINCT batch FROM students");
                                        while ($row1 = mysqli_fetch_assoc($query1)) {
                                            ?>
                                            <option value="<?php echo htmlspecialchars($row1['batch']); ?>" <?php echo $selectedBatch == $row1['batch'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($row1['batch']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-2 my-auto text-center">
                                    <h5> Subject:</h5>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control form-control-lg" aria-label=".form-select-lg example" name="Acadamic">
                                        <option selected hidden>--Select Subject-- </option>
                                        <?php
                                        $auser = $_SESSION['id'];
                                        $query1 = mysqli_query($con, "SELECT sub_name FROM subjects WHERE teacher_name='$auser'");
                                        while ($row1 = mysqli_fetch_assoc($query1)) {
                                            ?>
                                            <option value="<?php echo htmlspecialchars($row1['sub_name']); ?>" <?php echo $selectedAcademic == $row1['sub_name'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($row1['sub_name']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <br>
                                </div>

                                <div class="col-md-2 my-auto">
                                    <button type="submit" name="check" class="btn btn-info border-2"> Check Out
                                    </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br>



            <div class="container" style="margin-left: 10px;">
            <br>
            <div class="card shadow card-body">
                <h2>Existing Tables</h2>
                <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!empty($tables)):  ?>
                <div class="row">
                    <?php if (count($tables) > 0): ?>
                        <?php foreach ($tables as $table): ?>
                            <?php
                            $sql = "SELECT COUNT(*) AS count FROM " . htmlspecialchars($table) . " WHERE t_id = '$t_id'";
                            $query = $con->query($sql);
                            $result = $query->fetch_assoc();
                            $count = $result['count'];
                            ?>
                            <div class="col-xl-3 col-sm-6 col-lg-3" style="margin-top: 20px;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dash-widget-header ">
                                            <span class="dash-widget-icon">
                                                <i class="fa-solid fa-database" style='font-size:20px;'></i>
                                            </span>
                                        </div>
                                        <div class="dash-widget-info">
                                            <h3><?php echo $count; ?></h3>
                                            <h6 class="text-muted"><?php echo htmlspecialchars($table); ?></h6>
                                            <a href="dashboard_table_view.php?table=<?php echo urlencode($table); ?>"
                                                class="btn btn-primary item-center">View</a>
                                                <a href="print_data.php?table=<?php echo urlencode($table); ?>"
                                                class="btn btn-dark item-center">Print</a>
                                            <br><br>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-danger w-50"></div>
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
