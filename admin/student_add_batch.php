<?php
session_start();
require ("config.php");
////code

if (isset($_POST['remove'])) {
    $class = $_POST['class'];
    $batch = $_POST['batch'];
    $academic = $_POST['academic'];

    // SQL query to delete records
    $query = "DELETE FROM students WHERE class = '$class' AND batch = '$batch' AND join_year = '$academic'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $_SESSION['status'] = "Batch removed successfully!";
    } else {
        $_SESSION['status'] = "Failed to remove batch!";
    }
}


if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add student add batch </title>
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">



</head>

<body>
    <!-- Side-Nav -->

    <?php include ("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container  active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-2" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <h3 class="page-title"> Add Student </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> add Batch </li>
                </ol>
            </nav>

            <h5> Note:-</h5>
            <p> <label style="color: red;" for="">*</label>1.Once You Add the batch you can not change the <strong>
                    Class </strong> and <strong>Batch no </strong>and <strong>Name of Student </strong>
                <br><label style="color: red;" for="">*</label>2.If you want to change it delete the respective batch
                and add after the modification </>




                <?php if (isset($_SESSION['status'])) {
                    # code...
                    echo $_SESSION['status'];
                    unset($_SESSION['status']);

                }

                ?>

            <div>


                <form action="student_add_batch01.php" method="POST" enctype="multipart/form-data">
                    <div class="container  card  card-body shadow">
                        <div class="row">
                            <label for="formFileLg" class="form-label">upload Exel File to add batch in
                                system</label>
                            <div class="col-md-2 my-auto">
                                <h5>Select File</h5>

                            </div>
                            <div class="col-md-5">

                                <input class="form-control form-control-lg" id="formFileLg" name="import_file"
                                    type="file">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="import_file_btn" class="btn btn-primary"> Upload/Import
                                </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <br>

            <div>
                <h3 class="page-title"> Remove Batch </h3>


                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="container card card-body shadow">
                        <div class="row">
                            <div class="col-md-1 my-auto text-center">
                                <h5> Class:</h5>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="class">
                                    <option selected hidden>--Select Class-- </option>
                                    <?php
                                    $query1 = mysqli_query($con, "select DISTINCT class from students");
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        echo "<option value='{$row1['class']}' class='text-captalize'>{$row1['class']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1 my-auto text-center">
                                <h5> Batch:</h5>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="batch">
                                    <option selected hidden>--Select Batch-- </option>
                                    <?php
                                    $query1 = mysqli_query($con, "select DISTINCT batch from students");
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        echo "<option value='{$row1['batch']}' class='text-captalize'>{$row1['batch']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 my-auto text-center">
                                <h5> Academic Year:</h5>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="academic">
                                    <option selected hidden>--Select Academic Year-- </option>
                                    <?php
                                    $query1 = mysqli_query($con, "select DISTINCT join_year from students");
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        echo "<option value='{$row1['join_year']}' class='text-captalize'>{$row1['join_year']}</option>";
                                    }
                                    ?>
                                </select>
                                <br>
                            </div>
                            <div class="col-md-2 my-auto">
                                <button type="submit" name="remove" class="btn btn-danger">Remove</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>


            <div class="container">
                <br>
                <h3 class="page-title"> Student List </h3>
                <br>




                <div class="row card  card-body shadow ">
                    <div class="col-12">
                        <div class="data_table">
                            <table id="example" class="table table-striped responsive table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Student Name</th>
                                        <th>Class </th>
                                        <th>Batch</th>
                                        <th>Semester</th>
                                        <th>Academic Year</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $query = mysqli_query($con, "select * from students");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_row($query)) {
                                        ?>


                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['1']; ?></td>
                                            <td><?php echo $row['2']; ?></td>
                                            <td><?php echo $row['3']; ?></td>
                                            <td><?php echo $row['4']; ?></td>
                                            <td><?php echo $row['5']; ?></td>
                                            <td>
                                                <!-- <a href="subject_add_edit.php?id=<?php echo $row['0']; ?>"><button
                                                        class="btn btn-info"><i
                                                            class="fa-solid fa-pen-to-square"></i></button></a> -->
                                            </td>
                                        </tr>
                                        <?php
                                        $cnt = $cnt + 1;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <!-- bootstrap js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/style.js"></script>


</body>

</html>