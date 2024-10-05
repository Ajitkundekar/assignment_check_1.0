<?php
include("config.php");
$error = "";
$msg = "";

if (isset($_REQUEST['reg'])) {
    $name = $_REQUEST['name'];
    $email = $_REQUEST['email'];
    $phone = $_REQUEST['phone'];
    $qualification = $_REQUEST['qualification'];
    $experience = $_REQUEST['experience'];
    $branch = $_REQUEST['branch'];
    $pass = $_REQUEST['pass'];

    $uimage = $_FILES['uimage']['name'];
    $temp_name1 = $_FILES['uimage']['tmp_name'];
    $pass = sha1($pass);

    $query = "SELECT * FROM teacher WHERE email='$email'";
    $res = mysqli_query($con, $query);
    $num = mysqli_num_rows($res);

    if ($num == 1) {
        $error = "<p class='alert alert-warning'>Email Id already exists</p>";
    } else {
        if (!empty($name) && !empty($email) && !empty($phone) && !empty($pass) && !empty($uimage)) {
            $sql = "INSERT INTO teacher (name, phone, qualification, experience, image, branch, email, pass) 
                    VALUES ('$name', '$phone', '$qualification', '$experience', '$uimage', '$branch', '$email', '$pass')";
            $result = mysqli_query($con, $sql);
            move_uploaded_file($temp_name1, "admin/assets/teacher_img/$uimage");

            if ($result) {
                $msg = "<p class='alert alert-success'>Registered Successfully</p>";
            } else {
                $error = "<p class='alert alert-warning'>Registration Not Successful</p>";
            }
        } else {
            $error = "<p class='alert alert-warning'>Please fill all the fields</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Teacher Registration</title>
    <style>
        .login-form {
            width: 450px;
            padding: 2rem 1rem 1rem;
            margin-top: 0%;
        }

        form {
            padding: 1rem;
        }
    </style>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/all.min.css" rel="stylesheet" />
    <link href="assets/css/fontawesome.min.css" rel="stylesheet" />
    <!-- Bootstrap core JS-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="assets/js/scripts.js"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <!-- Header -->
        <!-- /Header -->
        <!-- Page content-->
        <div class="container">
            <div class="wrapper d-flex align-items-center justify-content-center h-100">
                <div class="login-form">
                    <div class="card-body card shadow" style="background-color: #f3faf2;">
                        <h2 class="card-title text-center"> Register </h2>
                        <?php if ($msg) { echo $msg; } ?>
                        <?php if ($error) { echo $error; } ?>

                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <span>Name:</span>
                                <input type="text" name="name" class="form-control" required placeholder="Your Name*">
                            </div>
                            <div class="form-group">
                                <span>Email:</span>
                                <input type="email" name="email" class="form-control" placeholder="Your Email*" required>
                            </div>
                            <div class="form-group">
                                <span>Phone:</span>
                                <input type="text" name="phone" class="form-control" placeholder="Your Phone*" required>
                            </div>
                            <div class="form-group">
                                <span>Qualification:</span>
                                <input type="text" name="qualification" class="form-control" placeholder="Your Qualification*" required>
                            </div>
                            <div class="form-group">
                                <span>Experience:</span>
                                <input type="text" name="experience" class="form-control" placeholder="Your Experience*" required>
                            </div>
                            <div class="form-group">
                                <span>Branch:</span>
                                <input type="text" name="branch" class="form-control" placeholder="Your Branch*" required>
                            </div>
                            <div class="form-group">
                                <span>Password:</span>
                                <input type="password" name="pass" class="form-control" placeholder="Your Password*" required>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label"><b>User Image</b></label>
                                <input class="form-control" name="uimage" type="file" required>
                            </div>
                            <br>
                            <button class="btn btn-primary btn-block w-100" style=" " name="reg" value="Register" type="submit">Register</button>
                            <div class="sign-up mt-4 text-center">
                                already have an account? <a href="index.php">Go to Login</a>
                            </div>
                        </form>

                       
                    </div>
                </div>
            </div>
        </div>
    </div>
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
