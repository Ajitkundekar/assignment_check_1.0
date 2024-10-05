<?php
session_start();
require("config.php");

// Check if it's an AJAX request and a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['column'])) {
    $name = $_POST['name'];
    $column = $_POST['column'];

    // Update the ending_date in the database
    $stmt = $con->prepare("UPDATE your_table SET ending_date = CURRENT_DATE() WHERE t_name = ? AND column_name = ?");
    $stmt->bind_param("ss", $_SESSION['auser'], $column);
    $stmt->execute();

    // Return success message or error handling if needed
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update ending_date']);
    }

    exit();
}
?>
