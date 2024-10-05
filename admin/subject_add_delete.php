<?php
include("config.php");
$sid = $_GET['id'];
$sql = "DELETE FROM subjects WHERE s_id = {$sid}";
$result = mysqli_query($con, $sql);
if($result == true)
{
	$msg="<p class='alert alert-success'>State Deleted</p>";
	header("Location:subject_add.php?msg=$msg");
}
else{
	$msg="<p class='alert alert-warning'>State Not Deleted</p>";
	header("Location:subject_add.php?msg=$msg");
}
mysqli_close($con);
?>