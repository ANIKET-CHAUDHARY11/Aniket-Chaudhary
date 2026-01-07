<?php
session_start();
include('dbconfig.php');
error_reporting(1);

$user = $_SESSION['faculty_login'];
if ($user == "") {
    header('location:home2.php');
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM faculty WHERE email='$user'");
$users = mysqli_fetch_assoc($sql);

// Handle attendance course selection
$select_query_result = null;
if (isset($_POST['course1'])) {
    $course_id = mysqli_real_escape_string($conn, $_POST['course1']);
    $select_query = "
        SELECT course_registration.student_id, user.name 
        FROM course_registration 
        INNER JOIN user ON course_registration.student_id = user.id 
        WHERE course_registration.status = 'Accepted' 
        AND course_registration.course_id = '$course_id'";
    $select_query_result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>STIP</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta name="viewport" content="width=device-width"/>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/animate.min.css" rel="stylesheet"/>
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
    <link href="assets/css/demo.css" rel="stylesheet"/>

    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet"/>

    <style>
        .main-panel {
            background-image: url('assets/img/image7.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body>
<div class="wrapper">
    <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">
        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="#" class="simple-text">
                    Hello <?php echo htmlspecialchars($users['Name']); ?>
                </a>
                <br>
                <img src="images/f1.jpeg" style="width:200px;height:180px;border-radius:50%">
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="faculty/index.php">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="faculty/Update_profile1.php">
                        <i class="pe-7s-user"></i>
                        <p>View/Edit Profile</p>
                    </a>
                </li>
                <li>
                    <a href="tea_co_reg.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>Approve Courses</p>
                    </a>
                </li>
                <li>
                    <a href="tea_atte.php">
                        <i class="pe-7s-id"></i>
                        <p>Give Attendance</p>
                    </a>
                </li>
                <li>
                    <a href="tea_view_att.php">
                        <i class="pe-7s-look"></i>
                        <p>View Attendance</p>
                    </a>
                </li>
                <li>
                    <a href="faculty/Forum1.php">
                        <i class="pe-7s-notebook"></i>
                        <p>Q-A Forum</p>
                    </a>
                </li>
                <li>
                    <a href="faculty/Upload.php">
                        <i class="pe-7s-upload"></i>
                        <p>Upload Study Material/Assignment</p>
                    </a>
                </li>
                <li>
                    <a href="faculty/view1.php">
                        <i class="pe-7s-look"></i>
                        <p>View Assignment Submissions</p>
                    </a>
                </li>
                <li>
                    <a href="faculty/Feedback1.php">
                        <i class="pe-7s-like2"></i>
                        <p>View Feedback</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-inverse navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                            data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="faculty/logout.php">
                                <p>Log out</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content" style="color: black; margin-bottom: 30px">
            <div class="container-fluid">
                <div class="row panel panel-default">
                    <div class="col-md-12">
                        <div style="margin-top: 30px;margin-bottom: 20px">

                            <!-- ✅ FIXED ALERT MESSAGE CODE -->
                            <?php
                            if (isset($_GET['message'])) {
                                $msg = htmlspecialchars($_GET['message']);
                                echo "<script>alert('$msg');</script>";
                            }
                            ?>

                            <h4>Select course to mark attendance:</h4>
                            <form method="post" action="tea_atte.php">
                                <table>
                                    <tr>
                                        <td>
                                            <div class="form-inline">
                                                <select class="form-control" name="course1" required>
                                                    <option value="" selected disabled>Select Course</option>
                                                    <?php
                                                    if (isset($_SESSION['course'])) {
                                                        foreach ($_SESSION['course'] as $course) {
                                                            echo "<option value='" . htmlspecialchars($course) . "'>$course</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-inline" style="margin-left: 20px">
                                                <input type="date" class="form-control" name="date" required>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="submit" class="btn btn-primary" style="margin-left: 20px"
                                                   name="att1" value="View Students">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <br>

                            <?php
                            if ($select_query_result && mysqli_num_rows($select_query_result) > 0) {
                                ?>
                                <form method="post"
                                      action="teacher_attendance_script.php?courseid=<?php echo urlencode($_POST['course1']); ?>&date=<?php echo urlencode($_POST['date']); ?>">
                                    <table class="table table-bordered" style="background: #fff;">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Present</th>
                                            <th>Absent</th>
                                        </tr>

                                        <?php
                                        while ($row = mysqli_fetch_assoc($select_query_result)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $row['student_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                <td>
                                                    <input type="checkbox" name="present[]" value="<?php echo $row['student_id']; ?>"> Present
                                                </td>
                                                <td>
                                                    <input type="checkbox" name="absent[]" value="<?php echo $row['student_id']; ?>"> Absent
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                    <input type="submit" class="btn btn-primary"
                                           style="margin-top: 20px; margin-left: 45%" value="Submit Attendance">
                                </form>
                                <?php
                            } elseif (isset($_POST['att1'])) {
                                echo "<p><strong>No students registered for this course.</strong></p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- main panel -->
</div> <!-- wrapper -->

<script src="assets/js/jquery.3.2.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<script src="assets/js/demo.js"></script>

</body>
</html>
