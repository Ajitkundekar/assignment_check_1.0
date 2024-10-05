<?php
session_start();
require("config.php");

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
    exit;
}

$name = $_SESSION['id'];
$name = mysqli_real_escape_string($con, $name); // Escape the variable for security

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_name = mysqli_real_escape_string($con, $_POST['name']);
    $updated_email = mysqli_real_escape_string($con, $_POST['email']);
    $updated_phone = mysqli_real_escape_string($con, $_POST['phone']);
    $updated_branch = mysqli_real_escape_string($con, $_POST['branch']);
    $updated_experience = mysqli_real_escape_string($con, $_POST['experience']);

    $update_sql = "UPDATE teacher SET 
                    name='$updated_name', 
                    email='$updated_email', 
                    phone='$updated_phone', 
                    branch='$updated_branch', 
                    experience='$updated_experience' 
                    WHERE id='$name'";
                    
    if (mysqli_query($con, $update_sql)) {
        echo "<script>alert('Profile updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating profile');</script>";
    }
}

$sql = "SELECT * FROM teacher WHERE id = '$name'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Profile</title>
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
                    <div class="container-fluid ">
                        <h3 class="page-title"> Profile </h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Profile</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                        <div class="container">
                            <div class="row gutters">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="account-settings">
                                                <div class="user-profile">
                                                    <div class="user-avatar ">
                                                        <img src="assets/teacher_img/<?php echo $row['image']; ?>" height="200"
                                                            width="250" alt="Profile Image">
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <strong>Name:</strong><?php echo $row['name']; ?>
                                                        </h5>
                                                        <p class="card-text"> <Strong> Email:</Strong>
                                                            <?php echo $row['email']; ?>
                                                            <br>
                                                            <Strong> Phone:</Strong> <?php echo $row['phone']; ?> <br>
                                                            <Strong> branch:</Strong> <?php echo $row['branch']; ?><br>

                                                            <Strong> Exprience: </Strong> <?php echo $row['experience']; ?></>



                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <form method="POST" action="">
                                                <div class="row gutters">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <h6 class="mb-2 text-primary">Personal Details</h6>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label for="fullName">Full Name</label>
                                                            <input type="text" class="form-control" id="fullName" name="name" value="<?php echo $row['name']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label for="eMail">Email</label>
                                                            <input type="email" class="form-control" id="eMail" name="email" value="<?php echo $row['email']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label for="phone">Phone</label>
                                                            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label for="branch">Branch</label>
                                                            <input type="text" class="form-control" id="branch" name="branch" value="<?php echo $row['branch']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label for="experience">Experience</label>
                                                            <input type="text" class="form-control" id="experience" name="experience" value="<?php echo $row['experience']; ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row gutters">
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-0 col-4">
                                                        
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-8 col-4">
                                                        <div class="text-right">
                                                            <button type="button" id="cancel" name="cancel" class="btn btn-secondary">Cancel</button>
                                                            <button type="submit" id="submit" name="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-0 col-4">
                                                        
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
        <?php
    }
} else {
    echo "No records found";
}
?>
