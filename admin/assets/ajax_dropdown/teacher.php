<?php
session_start();
require("config.php");

// Retrieve the subject name from POST data
$sub_name_data = $_POST['sub_name_data'];

// Query to join subjects with the teachers table and get the relevant data
$branch1 = "SELECT  t.id, t.name 
            FROM subjects s
            JOIN teacher t ON s.teacher_name = t.id
            WHERE s.s_id = '$sub_name_data'";

$state_qry = mysqli_query($con, $branch1);

// Initialize the output with a hidden option
$output = '<option value="" hidden>Select teacher</option>';

// Loop through the result and build the dropdown options
while ($state_row = mysqli_fetch_assoc($state_qry)) {
    $output .= '<option value="' . $state_row['id'] . '">' . $state_row['name'] . '</option>';
}

// Output the dropdown options
echo $output;
?>
