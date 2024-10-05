<?php
session_start();
require ("config.php");
////code

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
$error = "";
$msg = "";
if (isset($_POST['insert'])) {
    $sub_name = $_POST['sub_name'];
    $sub_code = $_POST['sub_code'];
    $class = $_POST['class'];
    $semester = $_POST['semester'];
    $teacher = $_POST['teacher'];
    $sid = $_GET['id'];

    if (!empty($sub_name) && !empty($sub_code) && !empty($teacher)) {
        $sql = "UPDATE subjects SET  sub_name= '{$sub_name}',sub_code = '{$sub_code}' ,class = '{$class}',semester = '{$semester}'  ,teacher_name= '{$teacher}' WHERE s_id = {$sid}";
        $result = mysqli_query($con, $sql);
        if ($result) {
			$msg="<p class='alert alert-success'>subject Updated</p>";
			header("Location:subject_add.php?msg=$msg");
        } else {
            $msg="<p class='alert alert-warning'>subject Not Updated</p>";
			header("Location:subject_add.php?msg=$msg");        }
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
    <title>subject edit</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">



</head>

<body>
    <!-- Side-Nav -->

    <?php include ("header.php"); ?>

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

            <h5> Note</h5>
        
           

            <div>

                <?php
                $sid = $_GET['id'];
                $sql = "SELECT * FROM subjects   where s_id = {$sid}";
                $result = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_row($result)) {
                    ?>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="  card  card-body shadow">
                            <div class="row">
                                <h5>Add Subject </h5>

                                <label for="formFileLg" class="form-label">upload Exel File to add batch in
                                    system</label>
                                <div class="col-md-2 my-auto text-left">
                                    <h5>Subject Name:</h5>

                                </div>
                                <div class="col-md-4">


                                    <input type="text" class="form-control form-control-lg" name="sub_name"
                                        placeholder=" Enter Subject Name" value="<?php echo $row['1']; ?>">
                                </div>
                                <div class="col-md-2 my-auto text-left">
                                    <h5>Subject Code:</h5>

                                </div>
                                <div class="col-md-4">


                                    <input type="text" class="form-control form-control-lg" name="sub_code"
                                        placeholder=" Enter Subject Code" style="text-transform: uppercase;"
                                        value="<?php echo $row['2']; ?>">
                                </div>
                                <br><br><br>
                                <div class="col-md-2 my-auto text-left">
                                    <h5>Class Name :</h5>

                                </div>
                                <div class="col-md-4">


                                    <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                        name="class">
                                        <option hidden selected><?php echo $row['3']; ?></option>
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
                                        <option hidden selected><?php echo $row['4']; ?></option>
                                        <option value="Semester One">Semester One</option>
                                        <option value="Semester Two">Semester Two</option>
                                        <option value="Semester Three">Semester Three</option>
                                        <option value="Semester Four">Semester Four</option>
                                        <option value="Semester Five">Semester Five</option>
                                        <option value="Semester Six">Semester Six</option>
                                    </select>
                                </div>
                                <br><br><br>
                                <div class="col-md-2 my-auto text-left">
                                    <h5>Assign Teacher :</h5>

                                </div>
                                <div class="col-md-4">


                                    <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                        name="teacher">
                                        <!-- <option selected hidden><?php echo $row['5']; ?></option> -->
                                         <option disable> select</option>
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
                                    <button type="submit" name="insert" class="btn btn-primary"> submit

                                </div>
                                <div class="col-md-5">
                                </div>

                            </div>
                        </div>
                    </form>
                <?php } ?>
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