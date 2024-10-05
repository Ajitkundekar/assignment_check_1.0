<?php
session_start();
require ("config.php");
////code

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Teacher List </title>
  <link rel="stylesheet" href="assets/css/bootstrap.css">
  <link rel="stylesheet" href="assets/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">



</head>

<body>
  <!-- Side-Nav -->
  
  <?php include("header.php"); ?>

  <!-- Main Wrapper -->
  <div class="p-3 my-container active-cont" >
    <!-- Top Nav -->
    <nav class="navbar top-navbar navbar-light bg-light px-5">
      <a class="btn border-0" id="menu-btn">            
      <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
      </a>
    </nav>
    <!--End Top Nav -->
    <div class="container" style="margin-left: 10px;">
      <br>
   <!-- Page Header -->
   <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Techer List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active">teacher</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <?php

                $query = mysqli_query($con, "select * from teacher");
                $cnt = 1;
                while ($row = mysqli_fetch_row($query)) {
                    ?>
                    <div class="col-xl-3 col-sm-7 col-md-6 col-lg-4   " style="">
                        <div class="card card2  " style="width: 103%; ">
                            <img class="cimg"
                                src=" assets/teacher_img/<?php echo $row['5']; ?>"
                                class="card-img-top shadow" alt="..." height="200" width="" style="border-radius: 60%; box-shadow: 2px 1px 2px ; border: 1px solid black; margin: 10px;"> 
                            <div class="card-body">
                                <h5 class="card-title"> <strong>Name:</strong><?php echo $row['1']; ?></h5>
                                <p class="card-text"> <Strong> Email:</Strong> <?php echo $row['7']; ?> <br>
                                 <Strong> Phone:</Strong> <?php echo $row['2']; ?> <br>
                                 <Strong> branch:</Strong> <?php echo $row['6']; ?><br>

                                 <Strong> Exprience: </Strong> <?php echo $row['4']; ?></>
                                <?php if( $row['9']==1){
                                    echo '<p><a href="teacher_status.php?id='.$row['0'].'&status=0" class="btn  btn-primary" style="align-items: center; transition:all 1s;">Active</a> </p> ';
                                }  
                                else{
                                    echo '<p><a href="teacher_status.php?id='.$row['0'].'&status=1" class="btn  btn-danger" style="align-items: center;">Inactive</a> </p> ';

                                }?>


                            </div>
                        </div>
                    </div>


                    <?php

                }
                ?>


            </div>
    
               </div>
  </div>

  <!-- bootstrap js -->
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
    
</body>

</html>