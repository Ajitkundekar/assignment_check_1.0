<?php
include("config.php"); // Include your database configuration file

$error = "";
$msg = "";

if (isset($_POST['reg'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // Check if passwords match
    if ($pass !== $confirm_pass) {
        $error = "<p class='alert alert-warning'>Passwords do not match</p>";
    } else {
        // Hash the password using password_hash
        $pass = ($pass);

        // Check if email already exists
        $query = "SELECT * FROM admin WHERE email='$email'";
        $res = mysqli_query($con, $query);
        $num = mysqli_num_rows($res);

        if ($num == 1) {
            $error = "<p class='alert alert-warning'>Email Id already exists</p>";
        } else {
            // Ensure required fields are filled
            if (!empty($name) && !empty($email) && !empty($pass)) {
                // Insert into admin table
                $sql = "INSERT INTO admin (name, email, pass) VALUES ('$name', '$email', '$pass')";
                $result = mysqli_query($con, $sql);

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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Registration</title>
    <style>
        .login-form {
            width: 450px;
            padding: 2rem;
            margin: auto;
            margin-top: 5%;
        }
        form {
            padding: 1rem;
        }
    </style>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="login-form">
            <div class="card-body card shadow" style="background-color: #f3faf2;">
                <h2 class="card-title text-center">Admin Register</h2>
                <?php if ($msg) { echo $msg; } ?>
                <?php if ($error) { echo $error; } ?>

                <form method="post" action="" onsubmit="return validatePasswords();">
                    <div class="form-group">
                        <span>Name:</span>
                        <input type="text" name="name" class="form-control" required placeholder="Your Name*">
                    </div>
                    <div class="form-group">
                        <span>Email:</span>
                        <input type="email" name="email" class="form-control" placeholder="Your Email*" required>
                    </div>
                    <div class="form-group">
                        <span>Password:</span>
                        <input type="password" name="pass" class="form-control" placeholder="Your Password*" required>
                    </div>
                    <div class="form-group">
                        <span>Confirm Password:</span>
                        <input type="password" name="confirm_pass" class="form-control" placeholder="Confirm Your Password*" required>
                    </div>
                    <br>
                    <button class="btn btn-primary btn-block w-100" name="reg" value="Register" type="submit">Register</button>
                    <div class="sign-up mt-4 text-center">
                        Already have an account? <a href="index.php">Go to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Confirmation -->
    <script>
        function validatePasswords() {
            var pass = document.querySelector('input[name="pass"]').value;
            var confirmPass = document.querySelector('input[name="confirm_pass"]').value;

            if (pass !== confirmPass) {
                alert("Passwords do not match");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

</body>
</html>
