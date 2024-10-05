<?php

include ('config.php');
$id = $_GET['id'];
$status = $_GET['status'];
mysqli_query($con, "update teacher set status= $status where id = $id");
header('location:teacherlist.php');

?>