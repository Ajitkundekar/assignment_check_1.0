<?php
session_start();
require("config.php");
////code

if (!isset($_SESSION['auser'])) {
    header("location:index.php");
}
$error = "";
$msg = "";


// Fetch all tables from the database
$tables_result = $con->query("SHOW TABLES");
$tables = [];
if ($tables_result) {
    while ($row = $tables_result->fetch_array()) {
        $tables[] = $row[0];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assignment</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script src="assets/js/jquery-3.6.0.min.js"></script>


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
            <h3 class="page-title"> Create Assignment </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"> Create Assignment </li>
                </ol>
            </nav>

            <h5> Note</h5>

            <?php if (isset($_SESSION['status'])) {
                # code...
                echo $_SESSION['status'];
                unset($_SESSION['status']);


            }

            ?>

            <div>


                <form action="subject_Assignment_add01.php" method="POST" enctype="multipart/form-data">
                    <div class="  card  card-body shadow">
                        <div class="row">
                            <h5>create a Subject Assignment</h5>


                            <div class="col-md-2 my-auto text-left">
                                <h5>Branch:</h5>

                            </div>
                            <div class="col-md-4">


                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="branch" id="branch" required>
                                    <option selected hidden value="">--Select Branch-- </option>


                                    <?php
                                    $query1 = mysqli_query($con, "SELECT DISTINCT class FROM subjects");
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        ?>
                                        <option value="<?php echo $row1['class']; ?>" class="text-capitalize">
                                            <?php echo $row1['class']; ?>
                                        </option>
                                    <?php } ?>
                                </select>


                            </div>
                            <div class="col-md-2 col-2 text-left">
                                <h5>Semester:</h5>

                            </div>
                            <div class="col-md-4">


                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="Semester" id="Semester" required>
                                    <option selected hidden value="">--Select Semester--</option>

                                </select>
                            </div>
                            <br><br><br>
                            <div class="col-md-2 my-auto text-left">
                                <h5>Batch:</h5>

                            </div>
                            <div class="col-md-4">


                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="batch" required>
                                    <option selected hidden value="">--Select batch--</option>
                                    <?php
                                    $query1 = mysqli_query($con, "SELECT DISTINCT batch FROM students");
                                    while ($row1 = mysqli_fetch_assoc($query1)) {
                                        ?>
                                        <option value="<?php echo $row1['batch']; ?>" class="text-capitalize">
                                            <?php echo $row1['batch']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-2 my-auto text-left">
                                <h5> Subject Name:</h5>

                            </div>
                            <div class="col-md-4">


                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="sub_id" id="sub_id" required>
                                    <option selected hidden value="">--Select Subject-- </option>

                                </select>


                            </div>
                            <br><br><br>

                            <div class="col-md-2 my-auto text-left">
                                <h5>teacher Name :</h5>

                            </div>
                            <div class="col-md-4">
                                <select class="form-control form-control-lg" aria-label=".form-select-lg example"
                                    name="t_id" id="t_id" required>
                                    <option selected hidden value="">--Select teacher-- </option>

                                </select>
                            </div>

                            <div class="col-md-4 my-auto text-left">
                                <h5>Enter number of Assignment:</h5>

                            </div>
                            <div class="col-md-2">

                                <input type="number" class="form-control form-control-lg" id="num_columns"
                                    name="num_columns" min="0" required><br>


                            </div>

                            <div id="columns_container" style="width: 60%; margin-left:20%;"></div>



                            <div class="col-md-5">
                            </div>
                            <br>

                            <div class="col-md-2"> <br>
                                <button type="submit" name="insert" class="btn btn-primary"> Create

                            </div>
                            <div class="col-md-5">
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <br>
            <div class=" card shadow card-body">

                <h2>Existing Tables</h2>
                <?php if (count($tables) > 0): ?>
                    <ul>
                        <?php foreach ($tables as $table): ?>
                            <li class="nav-link" style="display: inline-block;"><?php echo htmlspecialchars($table); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tables found in the database.</p>
                <?php endif; ?>

            </div>









        </div>
    </div>
    <script>
        // branch-> Semester
        $('#branch').on('change', function () {
            var branch = this.value;
            // console.log(branch);
            $.ajax({
                url: 'assets/ajax_dropdown/Semester.php',
                type: "POST",
                data: {
                    branch_data: branch
                },
                success: function (result) {
                    $('#Semester').html(result);
                    console.log(result);
                }
            })
        });


        // branch Semester-subject

        $('#Semester').on('change', function () {
            var branch = $('#branch').val();
            var sem = $('#Semester').val();

            console.log(branch);
            console.log(sem);
            $.ajax({
                url: 'assets/ajax_dropdown/subject.php',
                type: "POST",
                data: {
                    branch_data: branch,
                    Semester_data: sem
                },
                success: function (result) {
                    $('#sub_id').html(result);
                    // console.log(result);
                }
            })
        });

        // branch teacher

        $(' #sub_id').on('change', function () {
            var sub_id = $('#sub_id').val();
            console.log(sub_id);

            $.ajax({
                url: 'assets/ajax_dropdown/teacher.php',
                type: "POST",
                data: {
                    sub_name_data: sub_id // If you want to send sub_idas well
                },
                success: function (result) {
                    $('#t_id').html(result);
                    console.log(result);
                }
            });
        });

    </script>
    <script>
        const numColumnsInput = document.getElementById('num_columns');
        const columnsContainer = document.getElementById('columns_container');

        numColumnsInput.addEventListener('input', function () {
            columnsContainer.innerHTML = '';  // Clear the container

            const numColumns = parseInt(numColumnsInput.value);
            for (let i = 0; i < numColumns; i++) {
                const label = document.createElement('label');
                label.textContent = `Assignment ${i + 1} name: `;
                const inputName = document.createElement('input');
                inputName.type = 'text';
                inputName.name = `column_names[]`;
                inputName.className = ' form-control ';
                inputName.required = true;
                inputName.value = `Unit${i + 1}`;
                inputName.style.width = '14%';
                inputName.placeholder = "Enter the Assignment Title";

                const typeLabel = document.createElement('label');
                // typeLabel.textContent = ' Type: ';
                const inputType = document.createElement('select');
                inputType.name = `column_types[]`;
                inputType.className = 'form-control form-control-lg ';
                inputType.required = true;

                inputType.hidden = true;

                const types = ['INT'];
                types.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;

                    option.textContent = type;
                    inputType.appendChild(option);
                });

                columnsContainer.appendChild(label);
                columnsContainer.appendChild(inputName);
                columnsContainer.appendChild(typeLabel);
                columnsContainer.appendChild(inputType);
                columnsContainer.appendChild(document.createElement('br'));
            }
        });
    </script>

    <!-- bootstrap js -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/style.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/pdfmake.min.js"></script>
    <script src="assets/js/vfs_fonts.js"></script>
    <script src="assets/js/custom.js"></script>


</body>

</html>