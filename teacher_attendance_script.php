<?php
session_start();
require('dbconfig.php');

// Check if 'present' is set before looping
if (isset($_POST['present'])) {
    foreach ($_POST['present'] as $att) {
        $insert_query = "INSERT INTO attendance (stud_id, course_id, status, date)
                         VALUES ('$att', '{$_GET['courseid']}', 'Present', '{$_GET['date']}')";
        mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
    }
}

// Check if 'absent' is set before looping
if (isset($_POST['absent'])) {
    foreach ($_POST['absent'] as $att) {
        $insert_query = "INSERT INTO attendance (stud_id, course_id, status, date)
                         VALUES ('$att', '{$_GET['courseid']}', 'Absent', '{$_GET['date']}')";
        mysqli_query($conn, $insert_query) or die(mysqli_error($conn));
    }
}

// Redirect back to attendance page with message
header('Location: tea_atte.php?message=Attendance marked successfully');
exit;
?>
