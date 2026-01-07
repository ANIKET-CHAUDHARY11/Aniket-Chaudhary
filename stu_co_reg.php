<?php
session_start();
include('dbconfig.php');

if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header('location: home2.php');
    exit();
}

$user = $_SESSION['user'];

// Get student info
$sql = mysqli_query($conn, "SELECT * FROM user WHERE email='$user'");
$users = mysqli_fetch_assoc($sql);
$name = $users['name'];
$semester = strtolower(trim($users['semester'])); // for matching

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Course Registration</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-image: url('assets/img/image7.jpg');
            background-size: cover;
            color: black;
        }
        .course-list {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            width: 60%;
            margin: 50px auto;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.3);
        }
        .course-item {
            font-size: 18px;
            padding: 10px;
            margin: 10px 0;
            border-radius: 8px;
        }
        .accepted { background-color: #90ee90; }
        .pending { background-color: #ffd700; }
        .rejected { background-color: #ff7f7f; }
    </style>
</head>

<body>
<div class="course-list">
    <h3 class="text-center">Welcome, <?php echo $name; ?> 👋</h3>
    <h4 class="text-center">Select your courses (Semester: <?php echo strtoupper($semester); ?>)</h4>
    <hr>

    <?php
    // 🔹 Fetch all courses (optionally you can later filter by semester)
    $query = "SELECT course_id, course_name, teacher_id, teacher_name FROM courses";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if (mysqli_num_rows($result) == 0) {
        echo "<p class='text-center text-danger'>No courses found!</p>";
    } else {
        echo '<form method="post" action="student_course_registration_script.php">';
        echo '<ul style="list-style-type:none;">';

        while ($row = mysqli_fetch_assoc($result)) {
            $course_id = $row['course_id'];
            $course_name = $row['course_name'];

            $check = mysqli_query($conn, "SELECT status FROM course_registration 
                                          WHERE student_id='{$_SESSION['id']}' 
                                          AND course_id='$course_id'");
            $status_row = mysqli_fetch_assoc($check);

            if ($status_row) {
                $status = $status_row['status'];
                $class = strtolower($status);
                echo "<li class='course-item $class'><b>$course_id - $course_name</b> ($status)</li>";
            } else {
                echo "<li class='course-item'>
                        <input type='checkbox' name='courses[]' value='$course_id'>
                        $course_id - $course_name
                      </li>";
            }
        }

        echo '</ul>';
        echo '<div class=\"text-center\"><button type=\"submit\" class=\"btn btn-primary\">Register</button></div>';
        echo '</form>';
    }
    ?>
</div>
</body>
</html>
