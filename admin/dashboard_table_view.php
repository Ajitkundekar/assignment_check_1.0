<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
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
            teacher on  teacher.id= $table.t_id
    ";

    $rows_result = $con->query($query);

    // Define the columns to display
    $columns = [];
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
            if ($field->name !== 'class' && $field->name !== 'batch' && $field->name !== 'sub_name' && $field->name !== 'name' && $field->name !== 'sub_id' && $field->name !== 'stud_id' && $field->name !== 'Id'&& $field->name !== 't_id') {
                $columns[] = $field->name;
            }
        }
    }
} else {
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Print subject Data </title>
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
            <div class="">
                <h2>Students and Subjects with Additional Fields</h2>
                
                <!-- Grid Format for Class, Batch, Subject Name, and Teacher Name -->
                <div class="row">
                    <div class="col-md-3">
                        <strong>Class:</strong> <?php echo htmlspecialchars($class); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Batch:</strong> <?php echo htmlspecialchars($batch); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Subject:</strong> <?php echo htmlspecialchars($subject_name); ?>
                    </div>
                    <div class="col-md-3">
                        <strong>Teacher Name:</strong> <?php echo htmlspecialchars($tname); ?>
                    </div>
                </div>
                
                <br>

                <div class="row">
                    <?php if ($rows_result->num_rows > 0): ?>
                        <table id="example" class="table table-striped responsive table-bordered">
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
                                                        <input type="checkbox" <?php echo $row[$column] ? 'checked' : ''; ?> disabled>
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
</body>

</html>
