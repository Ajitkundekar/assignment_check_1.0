<?php
session_start();
include("config.php");
$error = "";

if (isset($_POST['login'])) {
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $pass = sha1($pass);
    if (!empty($user) && !empty($pass)) {
        $query = "SELECT  id,email, name, pass FROM teacher WHERE email='$user' AND pass='$pass'AND status='1'";
        $result = mysqli_query($con, $query) or die(mysqli_error());
        $num_row = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);
        if ($num_row == 1) {
            $_SESSION['auser'] = $row['name'];
            $_SESSION['id'] = $row['id'];

            header("Location: dashboard.php");
        } else {
            $error = '*Invalid User Name and Password';
        }
    } else {
        $error = "*Please Fill all the Fileds!";
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
    <title>teacher login</title>
    <style>
        .login-form {
            width: 350px;
            padding: 2rem 1rem 1rem;
            margin-top: 10%;
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
                <div class="card login-form shadow" style="background-color: #f3faf2;">
                    <div class="card-body" >
                        <h2 class="card-title text-center"> Teacher Login </h2>
                        <p style="color:red;"><?php echo $error; ?></p>


                        <form method="post">
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Name</label>
                                <input type="text" name="user" class="form-control" placeholder="Enter Name">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" name="pass" class="form-control" placeholder="password">
                            </div>
                            <button class="btn btn-primary btn-block w-100" name="login" type="submit">Login</button>

                            <div class="sign-up mt-4">
                                Don't have an account? <a href="teacher_register.php">Create One</a> <br>
                                Forgot Password? <a href="Forgot_pass.php">Click Here</a> <br>

                            </div>
                        </form>
                    </div>
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