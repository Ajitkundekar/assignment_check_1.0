<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    echo "Unauthorized access";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['column']) || !isset($_POST['table']) || !isset($_POST['dateValue'])) {
        echo "Required data is missing.";
        exit();
    }

    $column = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['column']);
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', $_POST['table']);
    $dateValue = $_POST['dateValue'];

    // Create the update query
    $update_query = "UPDATE $table SET $column = '$dateValue'";

    // Execute the update query
    if (mysqli_query($con, $update_query)) {
        echo "All records updated successfully.";
    } else {
        echo "Error updating records: " . mysqli_error($con);
    }
}
?>
