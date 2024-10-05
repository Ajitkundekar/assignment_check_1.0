<?php

session_start();

require("config.php");
////code
$branch = $_POST['branch_data'];

$branch1 = "SELECT  DISTINCT Semester FROM subjects WHERE class = '$branch'";


$state_qry = mysqli_query($con, $branch1);
// $output="";
$output = '<option value="" hidden>Select Semester</option>';
while ($state_row = mysqli_fetch_assoc($state_qry)) {
    $output .= '<option value="' . $state_row['Semester'] . '">' . $state_row['Semester'] . '</option>';
}
echo $output;





