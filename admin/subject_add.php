<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
$error = "";
$msg = "";
if (isset($_POST['insert'])) {
    $sub_name = rtrim($_POST['sub_name']);
    $sub_code = $_POST['sub_code'];
    $class = $_POST['class'];
    $semester = $_POST['semester'];
    $teacher = $_POST['teacher'];

    if (!empty($sub_name) && !empty($sub_code) && !empty($teacher)) {
        $sql = "insert into subjects (sub_name,sub_code,class,semester,teacher_name) values('$sub_name','$sub_code','$class','$semester','$teacher')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $msg = "<p class='alert alert-success'>Subject Inserted Successfully</p>";
        } else {
            $error = "<p class='alert alert-warning'>* Subject Not Inserted</p>";
        }
    } else {
        $error = "<p class='alert alert-warning'>* Fill all the Fields</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Subject </title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Side-Nav -->
    <?php include("header.php"); ?>

    <!-- Main Wrapper -->
    <div class="p-3 my-container active-cont">
        <!-- Top Nav -->
        <nav class="navbar top-navbar navbar-light bg-light px-5">
            <a class="btn border-0" id="menu-btn">
                <i class="fa fa-list sidebar-icon" style="font-size:25px;"> </i>
            </a>
        </nav>
        <!--End Top Nav -->
        <div class="container" style="margin-left: 10px;">
            <br>
            <br>
            <h3 class="page-title"> Add Subject </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> add Subject </li>
                </ol>
            </nav>

            <?php echo $error; ?>
            <?php echo $msg; ?>
            <?php
            if (isset($_GET['msg']))
                echo $_GET['msg'];
            unset($msg);
            ?>

            <div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="card card-body shadow">
                        <div class="row">

                            <div class="col-md-2 my-auto text-left">
                                <h5>Subject Name:</h5>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg" name="sub_name"
                                    placeholder=" Enter Subject Name">
                            </div>
                            <div class="col-md-2 my-auto text-left">
                                <h5>Subject Code:</h5>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-lg" name="sub_code"
                                    placeholder=" Enter Subject Code" style="text-transform: uppercase;">
                            </div>
                            <br><br><br>
                            <div class="col-md-2 my-auto text-left">
                                <h5>Class Name :</h5>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="class">
                                    <option hidden selected>--Select Class--</option>
                                    <option value="MCA">MCA</option>
                                    <option value="MBA">MBA</option>
                                </select>
                            </div>

                            <div class="col-md-2 my-auto text-left">
                                <h5>Semester:</h5>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="semester">
                                    <option hidden selected>--Select Semester--</option>
                                    <option>Semester One</option>
                                    <option>Semester Two</option>
                                    <option>Semester Three</option>
                                    <option>Semester Four</option>
                                </select>
                            </div>
                            <br><br><br>
                            <div class="col-md-2 my-auto text-left">
                                <h5>Assign Teacher :</h5>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="teacher">
                                    <option selected hidden>--Select Teacher-- </option>
                                    <?php
                                    $query1 = mysqli_query($con, "select * from teacher where status='1'");
                                    while ($row1 = mysqli_fetch_row($query1)) {
                                        ?>
                                        <option value="<?php echo $row1['0']; ?>" class="text-captalize">
                                            <?php echo $row1['1']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                            </div>
                            <br><br><br>
                            <div class="col-md-5">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" name="insert" class="btn btn-primary"> Submit
                            </div>
                            <div class="col-md-5">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="container">
                <br>
                <h3 class="page-title"> Subject List </h3>
                <br>

                <div class="row card card-body shadow">
                    <div class="col-12">
                        <div class="data_table">
                            <table id="example" class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Subject Name</th>
                                        <th>Subject Code</th>
                                        <th>Class</th>
                                        <th>Semester</th>
                                        <th>Assigned Teacher</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = mysqli_query($con, "SELECT s.*, t.name as teacher_name FROM subjects s 
                                                                 JOIN teacher t ON s.teacher_name = t.id");
                                    
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($query)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt; ?></td>
                                            <td><?php echo $row['1']; ?></td>
                                            <td><?php echo $row['2']; ?></td>
                                            <td><?php echo $row['3']; ?></td>
                                            <td><?php echo $row['4']; ?></td>
                                            <td><?php echo $row['teacher_name']; ?></td>
                                            <td>
                                                <a href="subject_add_edit.php?id=<?php echo $row['0']; ?>"><button
                                                        class="btn btn-info"><i
                                                            class="fa-solid fa-pen-to-square"></i></button></a>
                                                <a href="subject_add_delete.php?id=<?php echo $row['0']; ?>"><button
                                                        class="btn btn-danger"><i
                                                            class="fa-solid fa-trash-arrow-up"></i></button></a>
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
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>
</body>

</html>