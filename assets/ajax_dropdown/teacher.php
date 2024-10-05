<?php

session_start();

require ("config.php");
////code
$sub_name_data=$_POST['sub_name_data'];

$branch1 = "SELECT  DISTINCT teacher_name FROM subjects WHERE sub_name= '$sub_name_data' ";


$state_qry = mysqli_query($con, $branch1);
// $output="";
$output = '<option value="" hidden>Select teacher</option>';
while ($state_row = mysqli_fetch_assoc($state_qry)) {
    $output .= '<option value="' . $state_row['teacher_name'] . '">' . $state_row['teacher_name'] . '</option>';
}
echo $output;


