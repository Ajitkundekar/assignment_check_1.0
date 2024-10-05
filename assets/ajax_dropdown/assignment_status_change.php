<?php
session_start();
require("config.php");

// Fetch the data from the POST request
$id = $_POST['id_data'];
$cname = $_POST['cname_data'];
$table_name = $_POST['table_data'];
$check_value = $_POST['checkvalue_data'];
$last_char = $_POST['lastChar_data'];
$current_date_time = date('d-m-Y'); // Format as day-month-year

// Sanitize input to prevent SQL injection
$id = mysqli_real_escape_string($con, $id);
$cname = mysqli_real_escape_string($con, $cname);
$table_name = mysqli_real_escape_string($con, $table_name);
$check_value = mysqli_real_escape_string($con, $check_value);
$last_char = mysqli_real_escape_string($con, $last_char);

// Fetch the Last_date_unit for the given ID
$fetch_date_query = "SELECT Last_date_unit$last_char FROM $table_name WHERE Id = '$id'";
$result = mysqli_query($con, $fetch_date_query);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $last_date_unit = $row["Last_date_unit$last_char"];

    // Format last_date_unit to day-month-year
    $last_date_unit_formatted = DateTime::createFromFormat('Y-m-d', $last_date_unit)->format('d-m-Y');

    // Calculate the difference in days between the current date and Last_date_unit
    $date1 = DateTime::createFromFormat('d-m-Y', $current_date_time);
    $date2 = DateTime::createFromFormat('d-m-Y', $last_date_unit_formatted);

    // If current date is earlier, show as on time, otherwise calculate delay
    if ($date1 < $date2) {
        $difference_in_days = '(on Time)';
    } else {
        $interval = $date1->diff($date2);
        $difference_in_days = '(' . $interval->days . ' days late)';
    }

    // If $check_value is 0, do not update the date or show any difference
    if ($check_value == 0) {
        $current_date_time = '';
        $difference_in_days = '';
    }

    // Create the update query to set the submission date and status
    $update_query = "UPDATE $table_name 
                     SET $cname = '$check_value', 
                         Submit_date$last_char = CONCAT('$current_date_time', ' ', '$difference_in_days') 
                     WHERE Id = '$id'";

    // Execute the update query
    if (mysqli_query($con, $update_query)) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
} else {
    echo "No record found or error fetching Last_date_unit.";
}
?>
