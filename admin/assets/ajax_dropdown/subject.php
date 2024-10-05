<?php

session_start();

require("config.php");
////code
$branch = $_POST['branch_data'];
$sem = $_POST['Semester_data'];

$branch1 = "SELECT  s_id, sub_name   FROM subjects WHERE class = '$branch' AND Semester = '$sem'";

 
$state_qry = mysqli_query($con, $branch1);
// $output="";
$output = '<option value="" hidden >Select Subject </option>';
while ($state_row = mysqli_fetch_assoc($state_qry)) {
    $output .= '<option value="' . $state_row['s_id'] . '">' . $state_row['sub_name'] . '</option>';
}
echo $output;



