<?php
session_start();
require ("config.php");
// Redirect if user is not logged in
if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit(); // Ensure no further execution
}
$table = $_GET['table'] ?? null;
if ($table) {
    // Sanitize the table name to prevent SQL injection
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
    // Fetch columns from the table
    $columns_result = $con->query("SHOW COLUMNS FROM $table");
    $columns = [];
    $dateColumns = [];
    if ($columns_result) {
        while ($row = $columns_result->fetch_assoc()) {
            if (!startsWith($row['Field'], 'Last_date_unit') && !in_array($row['Field'], ['t_name', 'branch', 'batch', 'subject_name'])) {
                // Exclude specific columns
                $columns[] = $row['Field'];
            }
            if (startsWith($row['Field'], 'Last_date_unit') || startsWith($row['Field'], 'submit_date')) {
                $dateColumns[] = $row['Field'];
            }
        }
    }
    // Fetch all rows from the table filtered by 't_name' column
    $rows_result = $con->query("SELECT * FROM $table WHERE t_name = '" . $_SESSION['auser'] . "'");
} else {
    header("location:index.php");
    exit(); // Ensure no further execution
}
// Helper function to check if a string starts with a specific substring
function startsWith($haystack, $needle)
{
    return strncmp($haystack, $needle, strlen($needle)) === 0;
}
// Helper function to format date to 'd-m-Y'
function formatDate($date)
{
    return date('d-m-Y', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Table - <?php echo htmlspecialchars($table); ?></title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <style>
        .date-inputs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Side-Nav -->
    <?php include ("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <br>
        <div class="container" style="margin-left: 10px;">
            <div class="card container shadow card-body" style="min-width: 68rem; position: initial;">
                <?php if (isset($_SESSION['status'])) {
                    echo $_SESSION['status'];
                    unset($_SESSION['status']);
                } ?>
                <h2>Table: <?php echo htmlspecialchars($table); ?></h2>
                <div class="date-inputs">
                    <?php
                    // Fetch the date values for each date column
                    $dateValues = [];
                    if ($rows_result) {
                        while ($row = $rows_result->fetch_assoc()) {
                            foreach ($dateColumns as $dateColumn) {
                                $dateValues[$dateColumn] = htmlspecialchars($row[$dateColumn]);
                            }
                        }
                        // Reset the pointer to the beginning of the result set
                        $rows_result->data_seek(0);
                    }
                    ?>
                    <?php foreach ($dateColumns as $dateColumn): ?>
                        <div>
                            <label
                                for="<?php echo htmlspecialchars($dateColumn); ?>"><?php echo htmlspecialchars($dateColumn); ?></label>
                            <input type="date" id="<?php echo htmlspecialchars($dateColumn); ?>"
                                name="<?php echo htmlspecialchars($dateColumn); ?>"
                                data-column="<?php echo htmlspecialchars($dateColumn); ?>"
                                value="<?php echo $dateValues[$dateColumn]; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="data_table">
                    <?php if (count($columns) > 0): ?>
                        <table id="example" class="table table-striped table-responsive table-bordered">
                            <thead>
                                <tr class="table-dark">
                                    <?php foreach ($columns as $column): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $rows_result->fetch_assoc()): ?>
                                    <tr>
                                        <?php foreach ($columns as $column): ?>
                                            <td>
                                                <?php if (startsWith($column, 'Unit')): ?>
                                                    <?php $isChecked = $row[$column] == 1 ? 'checked' : ''; ?>
                                                    <input type="checkbox" disabled
                                                        data-id="<?php echo htmlspecialchars($row['Id']); ?>"
                                                        data-name="<?php echo htmlspecialchars($column); ?>"
                                                        data-table="<?php echo htmlspecialchars($table); ?>" <?php echo $isChecked; ?>>
                                                <?php else: ?>
                                                    <?php echo htmlspecialchars($row[$column]); ?>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No columns found in the table.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('input[type="checkbox"]').change(function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var table = $(this).data('table');
                var isChecked = $(this).prop('checked');
                var Checkvalue = isChecked ? 1 : 0;
                var changeInfo = isChecked ? 'Checked: ' : 'Unchecked: ';
                console.log(changeInfo);
                var lastChar = name.slice(-1);
                changeInfo += 'ID: ' + id + ', Name: ' + name + ', Last character: ' + lastChar + ', Table: ' + table + Checkvalue;
                console.log(changeInfo);
                $.ajax({
                    url: 'assets/ajax_dropdown/assignment_status_change.php',
                    type: "POST",
                    data: {
                        id_data: id,
                        cname_data: name,
                        table_data: table,
                        checkvalue_data: Checkvalue,
                        lastChar_data: lastChar
                    },
                    success: function (result) {
                        console.log(result);
                        location.reload(); // Reload the page after successful update
                    }
                });
            });
            $('input[type="date"]').change(function () {
                var column = $(this).data('column');
                var table = "<?php echo htmlspecialchars($table); ?>";
                var dateValue = $(this).val();
                console.log(column);
                console.log(table);
                console.log(dateValue);
                $.ajax({
                    url: 'assets/ajax_dropdown/date_change.php',
                    type: "POST",
                    data: {
                        column: column,
                        table: table,
                        dateValue: dateValue
                    },
                    success: function (result) {
                        console.log(result);
                    }
                });
            });
        });
    </script>
    <!-- bootstrap js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>