<?php
session_start();
require("config.php");
// require 'vendor/autoload.php'; 
require 'assets/vendor/autoload.php';

use Dompdf\Dompdf;

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit();
}
function formatDate($date)
{
    if ($date === '00-00-0000' || empty($date)) {
        return '00-00-0000'; // Default value
    }

    // Convert to DateTime object
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime) {
        return $dateTime->format('d-m-Y'); // Format as DD-MM-YYYY
    }

    return '00-00-0000'; // Return default if invalid
}


$table = $_GET['table'] ?? null;

if ($table) {
    // Sanitize the table name to prevent SQL injection
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);

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

    // Create the HTML content for the PDF
    ob_start();
    ?>
   <html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        /* Add CSS for the border around the page */
        .page-border {
            border: 5px solid black;
            /* Set the border color and thickness */
            padding: 10px;
            /* Add some padding inside the border */
            margin: 1px;
            /* Optional: adds margin around the border */
        }

        table {
            width: 90%; /* Reduce table width */
            margin: 0 auto; /* Center table inside the bordered container */
            font-size: 12px; /* Reduce font size for the table */
        }

        th, td {
            padding: 5px;
        }

        /* Optional: You can add further styles to adjust table aesthetics */
    </style>
</head>

<body>
    <div class="page-border"> <!-- Wrap the entire content in a div with the border -->
        <h2 style="text-align: center;">kolhapur institute of technology's <br> institute of management & research
            <br>gokul-shirgaon kolhapur
        </h2><br>

        <!-- Single line format for Class, Batch, Subject Name, and Teacher Name -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 2px;">
            <div style="display: inline-block; margin-right: 140px;">
                <strong>Class:</strong> <?php echo htmlspecialchars($class); ?>
            </div>
            <div style="display: inline-block; margin-right: 140px;">
                <strong>Batch:</strong> <?php echo htmlspecialchars($batch); ?>
            </div>
            <div style="display: inline-block; margin-right: 140px;">
                <strong>Subject:</strong> <?php echo htmlspecialchars($subject_name); ?>
            </div>
            <div style="display: inline-block;">
                <strong>Teacher Name:</strong> <?php echo htmlspecialchars($tname); ?>
            </div>
        </div>

        <br><br><br>

        <!-- Display columns starting with "Last_date_unit" outside the table -->
        <div>
            <?php foreach ($extra_columns as $extra_column): ?>
                <div style="display: inline-block; margin-right: 150px; margin-bottom: 30px;">
                    <strong><?php echo htmlspecialchars($extra_column); ?>:</strong>
                    <input type="text"
                           value="<?php echo htmlspecialchars(formatDate($extra_column_value[$extra_column] ?? '00-00-0000')); ?>"
                           placeholder="DD-MM-YYYY">
                </div>
            <?php endforeach; ?>
        </div>

        <br>

        <!-- The table is now inside the bordered container -->
        <table border="1" cellpadding="5" cellspacing="0">
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
            $rows_result->data_seek(0); // Reset the result pointer
            while ($row = $rows_result->fetch_assoc()): ?>
                <tr>
                    <?php foreach ($columns as $column): ?>
                        <td>
                            <?php
                            if ($column === 'S.No'):
                                echo $serial_no++;
                            else:
                                // Check if the column name starts with "unit" and if its value is 0
                                if (strpos($column, 'Unit') === 0 && $row[$column] == '0') {
                                    echo ' N/A';
                                } elseif (strpos($column, 'Unit') === 0 && $row[$column] != '0') {
                                    echo ' Submitted';
                                } else {
                                    echo htmlspecialchars($row[$column]);
                                }
                            endif; ?>
                        </td>
                    <?php endforeach; ?>

                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div> <!-- Close the page-border div -->
</body>
<script>
    // Loop through all cells that are supposed to contain "unit" column data
    document.querySelectorAll('.unit-column').forEach(function (cell) {
        // Check if the cell has a value of '0'
        if (cell.textContent.trim() === '0') {
            cell.textContent = 'Not Submitted'; // Replace '0' with 'Not Submitted'
        } else if (!isNaN(cell.textContent) && cell.textContent.trim() !== '0') {
            cell.textContent = '✓'; // Replace other numeric values with the checkmark symbol
        }
    });
</script>

</html>

    <?php
    $html = ob_get_clean();


    // Create PDF instance and render HTML
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'Landscape');
    $dompdf->render();

    // Output the generated PDF
    $dompdf->stream('students_subjects.pdf', ['Attachment' => 0]); // Display inline, not as attachment
    exit();
} else {
    header("location:index.php");
    exit();
}
