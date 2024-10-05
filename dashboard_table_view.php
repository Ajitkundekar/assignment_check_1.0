<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}

$table = $_GET['table'] ?? null;

if ($table) {
    // Sanitize the table name to prevent SQL injection
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

    // Query to fetch specific columns using JOINs and include all columns from the specified table
    $query = "
        SELECT 
            students.stud_name, 
            students.class, 
            students.batch, 
            subjects.sub_name, 
            teacher.name,
            $table.* 
        FROM 
            students
        INNER JOIN 
            $table ON students.s_id = $table.stud_id
        INNER JOIN 
            subjects ON subjects.s_id = $table.sub_id
        INNER JOIN
            teacher on teacher.id = $table.t_id
    ";

    $rows_result = $con->query($query);

    // Define the columns to display
    $columns = [];
    $extra_columns = [];
    $extra_column_value = []; // Store one value per "Last_date_unit" column

    if ($rows_result) {
        $column_count = $rows_result->field_count;
        $first_row = $rows_result->fetch_assoc();
        $class = $first_row['class'] ?? '';
        $batch = $first_row['batch'] ?? '';
        $subject_name = $first_row['sub_name'] ?? '';
        $tname = $first_row['name'] ?? '';

        // Reset the result pointer to the beginning
        $rows_result->data_seek(0);

        // Adding a serial number column
        $columns[] = 'S.No';

        for ($i = 0; $i < $column_count; $i++) {
            $field = $rows_result->fetch_field_direct($i);
            if ($field->name !== 'class' && $field->name !== 'batch' && $field->name !== 'sub_name' && $field->name !== 'name' && $field->name !== 'sub_id' && $field->name !== 'stud_id' && $field->name !== 'Id' && $field->name !== 't_id') {
                if (strpos($field->name, 'Last_date_unit') === 0) {
                    $extra_columns[] = $field->name;
                } else {
                    $columns[] = $field->name;
                }
            }
        }

        // Fetch one value for each "Last_date_unit" column
        while ($row = $rows_result->fetch_assoc()) {
            foreach ($extra_columns as $extra_column) {
                // Only store the first value encountered for each column
                if (!isset($extra_column_value[$extra_column])) {
                    $extra_column_value[$extra_column] = $row[$extra_column];
                }
            }
        }
    } else {
        header("location:index.php");
        exit();
    }
} else {
    header("location:index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Table - Students and Subjects</title>
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
        <nav class="navbar top-navbar navbar-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <h2>Students and Subjects with Additional Fields</h2>
            <div class="">

                <!-- Grid Format for Class, Batch, Subject Name, and Teacher Name -->
                <div class="row">
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <strong>Class:</strong> <?php echo htmlspecialchars($class); ?>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <strong>Batch:</strong> <?php echo htmlspecialchars($batch); ?>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <strong>Subject:</strong> <?php echo htmlspecialchars($subject_name); ?>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-6">
                        <strong>Teacher Name:</strong> <?php echo htmlspecialchars($tname); ?>
                    </div>
                </div>

                <br>

                <!-- Display columns starting with "Last_date_unit" outside the table -->
                <div class="row">
                    <?php foreach ($extra_columns as $extra_column): ?>
                        <div class="col-md-3 col-xs-12 col-sm-12">
                            <strong><?php echo htmlspecialchars($extra_column); ?>:</strong>
                            <input type="date" id="<?php echo htmlspecialchars($extra_column); ?>"
                                name="<?php echo htmlspecialchars($extra_column); ?>"
                                data-column="<?php echo htmlspecialchars($extra_column); ?>"
                                value="<?php echo htmlspecialchars($extra_column_value[$extra_column] ?? ''); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <br>

                <div class="row">
                    <?php if ($rows_result->num_rows > 0): ?>
                        <table id="" class="table table-striped responsive table-bordered">
                            <thead>
                                <tr>
                                    <?php foreach ($columns as $column): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serial_no = 1; // Initialize serial number counter
                                $rows_result->data_seek(0); // Reset the result pointer to ensure all rows are processed
                                while ($row = $rows_result->fetch_assoc()): ?>
                                    <tr>
                                        <?php foreach ($columns as $column): ?>
                                            <td>
                                                <?php
                                                if ($column === 'S.No'):
                                                    echo $serial_no++; // Display serial number and increment
                                                else:
                                                    // Check if the column name starts with "Unit"
                                                    if (strpos($column, 'Unit') === 0): ?>
                                                        <?php $isChecked = $row[$column] == 1 ? 'checked' : ''; ?>
                                                        <input type="checkbox" data-id="<?php echo htmlspecialchars($row['Id']); ?>"
                                                            data-name="<?php echo htmlspecialchars($column); ?>"
                                                            data-table="<?php echo htmlspecialchars($table); ?>" <?php echo $isChecked; ?>>
                                                    <?php else: ?>
                                                        <?php echo htmlspecialchars($row[$column]); ?>
                                                    <?php endif;
                                                endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No data found.</p>
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
</body>

</html>