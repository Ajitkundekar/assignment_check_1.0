<?php
session_start();
require("config.php");
////code

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $branch = $con->real_escape_string($_POST['branch']);
    $batch = $con->real_escape_string($_POST['batch']);
    $sub_id = $con->real_escape_string($_POST['sub_id']);
    $t_id = $con->real_escape_string($_POST['t_id']);
    $id = $con->real_escape_string($_POST['Semester']); // ID of the subject table from the semester dropdown

    // Fetch the subject name based on sub_id
    $sub_name_query = "SELECT sub_name FROM subjects WHERE s_id = '$sub_id'";
    $result = $con->query($sub_name_query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sub_name = $row['sub_name'];
        $sub_name1 = str_replace(' ', '_', $sub_name);
    } else {
        $_SESSION['status'] = "<p class='alert alert-danger'>Invalid subject ID.</p>";
        header("location:subject_Assignment_add.php");
        exit();
    }

    // Validate column inputs
    $num_columns = intval($_POST['num_columns']);
    $column_names = isset($_POST['column_names']) ? $_POST['column_names'] : [];
    $column_types = isset($_POST['column_types']) ? $_POST['column_types'] : [];

    if ($num_columns > 0 && count($column_names) == $num_columns && count($column_names) == count($column_types)) {
        // Combine branch, batch, and sanitized subject name to create table name
        $table_name = strtolower($branch . $batch . "_" . $sub_name1);

        // Create the SQL statement to create the table
        $sql = "CREATE TABLE `$table_name` (
            Id INT AUTO_INCREMENT PRIMARY KEY,
            sub_id INT,
            stud_id INT,
            t_id INT 
        ";

        // Add user-specified columns
        for ($i = 0; $i < $num_columns; $i++) {
            $column_name = $con->real_escape_string($column_names[$i]);
            $column_type = $con->real_escape_string($column_types[$i]);
            $starting_date_column = "Last_date_unit" . ($i + 1);
            $ending_date_column = "Submit_date" . ($i + 1);
            $sql .= ", `$column_name` $column_type DEFAULT 0, `$starting_date_column` DATE, `$ending_date_column` VARCHAR(100)";
        }

        $sql .= ")";

        // Execute the query to create the table
        if ($con->query($sql) === TRUE) {
            $_SESSION['status1'] = "<p class='alert alert-success'>Table '$table_name' created successfully.</p>";

            // Insert data from the students table based on branch and batch
            $insert_sql = "INSERT INTO `$table_name` (stud_id, sub_id,t_id)
                           SELECT s_id,'$sub_id','$t_id'
                           FROM students 
                           WHERE class = '$branch' AND batch = '$batch'";

            if ($con->query($insert_sql) === TRUE) {
                $_SESSION['status'] = "<p class='alert alert-success'>Data inserted successfully into '$table_name'.</p>";
            } else {
                $_SESSION['status'] = "<p class='alert alert-danger'>Error inserting data: " . $con->error . "</p>";
            }

            header("location:subject_Assignment_add.php");
            exit(); // Prevent further script execution after redirect
        } else {
            $_SESSION['status'] = "<p class='alert alert-danger'>Error creating table: " . $con->error . "</p>";
            header("location:subject_Assignment_add.php");
            exit(); // Prevent further script execution after redirect
        }
    } else {
        $_SESSION['status'] = "<p class='alert alert-danger'>Invalid column data.</p>";
        header("location:subject_Assignment_add.php");
        exit(); // Prevent further script execution after redirect
    }
} else {
    $_SESSION['status'] = "<p class='alert alert-danger'>Invalid request.</p>";
    header("location:subject_Assignment_add.php");
    exit(); // Prevent further script execution after redirect
}
?>