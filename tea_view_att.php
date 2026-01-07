<?php
session_start();
include('dbconfig.php');
error_reporting(1);

$user = $_SESSION['faculty_login'];
if ($user == "") {
    header('location:home2.php');
    exit();
}

$sql = mysqli_query($conn, "SELECT * FROM faculty WHERE email='$user' ");
$users = mysqli_fetch_assoc($sql);

$cc = $users['course_code'];

$select_query = "SELECT stud_id, COUNT(stud_id) AS count FROM attendance WHERE course_id = '$cc' AND status = 'Present' GROUP BY stud_id";
$select_query_result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>STIP</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet" />
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!-- Fonts and icons -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css">
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

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
                    Hello <?php echo $users['Name']; ?>
                </a>
                <br>
                <img src="images/f1.jpeg" style="width:200px;height:180px;border-radius:50%">
            </div>

            <ul class="nav">
                <li><a href="faculty/index.php"><i class="pe-7s-graph"></i><p>Dashboard</p></a></li>
                <li><a href="faculty/Update_profile1.php"><i class="pe-7s-user"></i><p>View/Edit Profile</p></a></li>
                <li><a href="tea_co_reg.php"><i class="pe-7s-news-paper"></i><p>Approve Courses</p></a></li>
                <li><a href="tea_atte.php"><i class="pe-7s-id"></i><p>Give Attendance</p></a></li>
                <li class="active"><a href="tea_view_att.php"><i class="pe-7s-look"></i><p>View Attendance</p></a></li>
                <li><a href="faculty/Forum1.php"><i class="pe-7s-notebook"></i><p>Q-A forum</p></a></li>
                <li><a href="faculty/Upload.php"><i class="pe-7s-upload"></i><p>Upload Study Material</p></a></li>
                <li><a href="faculty/view1.php"><i class="pe-7s-look"></i><p>View Assignment submissions</p></a></li>
                <li><a href="faculty/Feedback1.php"><i class="pe-7s-like2"></i><p>View Feedback</p></a></li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-inverse navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="faculty/logout.php"><p>Log out</p></a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="content" style="color: black; margin-bottom: 30px">
            <div class="container-fluid">
                <div class="row panel panel-default">
                    <div class="col-md-6">

                        <div style="margin-top: 30px;margin-bottom: 20px">
                            <h4>Choose the course to see the attendance:</h4>
                            <form method="post" action="">
                                <table style="border-spacing: 100px;">
                                    <tr>
                                        <td>
                                            <div class="form-inline">
                                                <select class="form-control" name="course1" style="width: 100px">
                                                    <option value="" selected disabled>Course</option>
                                                    <?php
                                                    if (isset($_SESSION['course'])) {
                                                        foreach ($_SESSION['course'] as $course) {
                                                            echo "<option value='$course'>$course</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td><input type="submit" class="btn btn-primary" style="margin-left: 50px" name="att1"></td>
                                    </tr>
                                </table>

                                <table border="1" cellpadding="10" style="margin-top: 20px; border-collapse: collapse;">
                                    <tr style="background-color: #eee;">
                                        <th>Student Id</th>
                                        <th>Name</th>
                                        <th>Days Present</th>
                                    </tr>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($select_query_result)) {
                                        $stud_id = $row['stud_id'];
                                        $select_query1 = "SELECT name FROM user WHERE id = '$stud_id'";
                                        $select_query1_result = mysqli_query($conn, $select_query1) or die(mysqli_error($conn));
                                        $row1 = mysqli_fetch_assoc($select_query1_result);
                                        ?>
                                        <tr>
                                            <td><?php echo $row['stud_id']; ?></td>
                                            <td><?php echo $row1['name']; ?></td>
                                            <td><?php echo $row['count']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<!-- Core JS Files -->
<script src="assets/js/jquery.3.2.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/chartist.min.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>
<script src="assets/js/demo.js"></script>
</html>
