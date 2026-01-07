<?php
session_start();
include('dbconfig.php');

if (!isset($_SESSION['user']) || $_SESSION['user'] == "") {
    header('location: home2.php');
    exit();
}

$user = $_SESSION['user'];

// Student info
$sql = mysqli_query($conn, "SELECT * FROM user WHERE email='$user'");
$users = mysqli_fetch_assoc($sql);
$name = $users['name'];

// ✅ Check if any course is selected
if (!isset($_POST['courses']) || count($_POST['courses']) == 0) {
    echo "<script>alert('No course selected!'); window.location='stu_co_reg.php';</script>";
    exit();
}

foreach ($_POST['courses'] as $course) {
    // Find course and faculty info
    $select_query = "SELECT course_name, id, name FROM faculty WHERE course_code='$course'";
    $select_query_result = mysqli_query($conn, $select_query) or die(mysqli_error($conn));

    while ($row = mysqli_fetch_array($select_query_result)) {
        $insert_query = "INSERT INTO course_registration(student_id, name, course_id, course_name, teacher_id, teacher_name, status)
                         VALUES ('" . $_SESSION['id'] . "', '$name', '$course', '" . $row['course_name'] . "', '" . $row['id'] . "', '" . $row['name'] . "', 'Pending')";
        mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
    }
}

header('location: user/index.php');
exit();
?>
