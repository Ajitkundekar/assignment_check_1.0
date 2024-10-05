<?php
include("config.php");
$error = "";
$msg = "";
$email = "";
$email_verified = false; // Flag to check if the email is verified

// Step 1: Verify Email
if (isset($_REQUEST['verify_email'])) {
    $email = $_REQUEST['email'];

    // Check if the email exists in the database
    $query = "SELECT * FROM teacher WHERE email='$email'";
    $res = mysqli_query($con, $query);
    $num = mysqli_num_rows($res);

    if ($num == 1) {
        $email_verified = true; // Set the flag to true if email exists
    } else {
        $error = "<p class='alert alert-warning'>Email not found.</p>";
    }
}

// Step 2: Reset Password
if (isset($_REQUEST['reset_password'])) {
    $email = $_REQUEST['email'];
    $new_password = $_REQUEST['new_password'];
    $confirm_password = $_REQUEST['confirm_password'];

    if ($new_password == $confirm_password) {
        $hashed_password = sha1($new_password);

        // Update the password in the database
        $query = "UPDATE teacher SET pass='$hashed_password' WHERE email='$email'";
        $res = mysqli_query($con, $query);

        if ($res) {
            $msg = "<p class='alert alert-success'>Password reset successfully.</p>";
        } else {
            $error = "<p class='alert alert-warning'>Password reset failed. Please try again.</p>";
        }
    } else {
        $error = "<p class='alert alert-warning'>Passwords do not match.</p>";
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
    <title>Forgot Password</title>
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
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <script src="assets/js/bootstrap.js"></script>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <div class="container">
            <div class="wrapper d-flex align-items-center justify-content-center h-100">
                <div class="login-form">
                    <div class="card-body card shadow" style="background-color: #f3faf2;">
                        <h2 class="card-title text-center"> Forgot Password </h2>
                        <?php if ($msg) { echo $msg; } ?>
                        <?php if ($error) { echo $error; } ?>

                        <!-- If the email is not yet verified, show the email verification form -->
                        <?php if (!$email_verified && !isset($_REQUEST['reset_password'])) { ?>
                            <form method="post" action="">
                                <div class="form-group">
                                    <span>Email:</span>
                                    <input type="email" name="email" class="form-control" placeholder="Your Email*" required>
                                </div>
                                <br>
                                <button class="btn btn-primary btn-block w-100" name="verify_email" value="Verify Email" type="submit">Verify Email</button>
                            </form>
                        <?php } ?>

                        <!-- If the email is verified, show the new password form -->
                        <?php if ($email_verified || isset($_REQUEST['reset_password'])) { ?>
                            <form method="post" action="">
                                <div class="form-group">
                                    <span>New Password:</span>
                                    <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
                                </div>
                                <div class="form-group">
                                    <span>Confirm Password:</span>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                                </div>
                                <input type="hidden" name="email" value="<?php echo $email; ?>">
                                <br>
                                <button class="btn btn-primary btn-block w-100" name="reset_password" value="Reset Password" type="submit">Reset Password</button>
                            </form>
                        <?php } ?>

                        <div class="sign-up mt-4">
                            Back to <a href="index.php">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>

</html>
