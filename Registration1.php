<?php
session_start();
require('dbconfig.php');

// Redirect if already logged in
if (isset($_SESSION['user'])) {
    header('location: user/index.php');
    exit;
} elseif (isset($_SESSION['faculty_login'])) {
    header('location: faculty/index.php');
    exit;
} elseif (isset($_SESSION['admin'])) {
    header('location: admin');
    exit;
}

extract($_POST);

if (isset($save)) {
    $role = $_POST['role'];
    $imageName = $_FILES['img']['name'];
    $pass = md5($p);

    // Validate image
    if (!empty($imageName)) {
        $tmp_name = $_FILES['img']['tmp_name'];
    } else {
        $imageName = "";
    }

    // Student registration
    if ($role == "Student") {
        $sql = mysqli_query($conn, "SELECT * FROM user WHERE email='$e'");
        $r = mysqli_num_rows($sql);
        if ($r > 0) {
            $err = "<font color='red'><h3 align='center'>This user already exists</h3></font>";
        } else {
            $query = "INSERT INTO user(name, email, pass, mobile, programme, semester, gender, image, dob)
                      VALUES('$n','$e','$pass','$mob','$pro','$sem','$gen','$imageName','$yy')";
            mysqli_query($conn, $query) or die(mysqli_error($conn));

            $folderPath = "images/$e";
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            if (!empty($imageName)) {
                move_uploaded_file($_FILES['img']['tmp_name'], "$folderPath/$imageName");
            }

            $err = "<h3 align='center' style='color: blue'>Student Registration Successful!</h3>";
        }

    // Faculty registration
    } elseif ($role == "Faculty") {
        $sql = mysqli_query($conn, "SELECT * FROM faculty WHERE email='$e'");
        $r = mysqli_num_rows($sql);
        if ($r > 0) {
            $err = "<font color='red'><h3 align='center'>This faculty already exists</h3></font>";
        } else {
            $query = "INSERT INTO faculty(user_alias, Name, designation, programme, semester, email, password, mobile, date, status, course_name, course_code)
                      VALUES('$e','$n','$des','$pro','$sem','$e','$pass','$mob','$yy','0','$course','$course_code')";
            mysqli_query($conn, $query) or die(mysqli_error($conn));

            $folderPath = "images_faculty/$e";
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            if (!empty($imageName)) {
                move_uploaded_file($_FILES['img']['tmp_name'], "$folderPath/$imageName");
            }

            $err = "<h3 align='center' style='color: blue'>Faculty Registration Successful!</h3>";
        }
    } else {
        $err = "<font color='red'><h3 align='center'>Please select a valid role!</h3></font>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration | STIP</title>
    <link rel="stylesheet" href="css/main_reg.css">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet">

    <style>
        body {
            width: 100%;
            background: url("images/blur2.jpg") no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }
        select, input {
            font-size: 16px;
            color: black;
            padding: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        #facultyFields { display: none; }

        .btn {
            font-size: 16px;
            padding: 8px 20px;
            border-radius: 5px;
            border: none;
        }
        .btn-info {
            background-color: #28a745;
            color: white;
        }
        .btn-warning {
            background-color: #ff6600;
            color: white;
        }
    </style>
</head>

<body>

<header id="header" class="alt">
  <div class="logo"><a href="home.php">Welcome to <span>Student Teacher Interaction Portal</span></a></div>
  <a href="#menu">Menu</a>
</header>

<nav id="menu">
  <ul class="links">
    <li><a href="home.php">Home</a></li>
    <li><a href="About1.php">About</a></li>
    <li><a href="Registration1.php">Registration</a></li>
    <li class="dropdown">
      <a href="#">Login</a>
      <ul class="dropdown-menu">
        <li><a href="Login1.php">Student</a></li>
        <li><a href="Faculty_login1.php">Faculty</a></li>
        <li><a href="admin_login.php">Admin</a></li>
      </ul>
    </li>
  </ul>
</nav>

<div class="wrapper-style4">
  <header class="align-center">
    <h2 style="color:white; margin-top:20px">Registration Form</h2>
  </header>
</div>

<div class="signup-form" style="padding: 10px 50px 50px 300px">
  <div class="main-div">
    <div class="panel panel-default" style="padding: 30px 25px">
      <form method="post" enctype="multipart/form-data">
        <div style="color:red;"><?php echo @$err; ?></div>

        <div class="form-group">
          <select name="role" id="role" class="form-control" style="background-color:transparent;color:white" onchange="toggleFields()" required>
            <option value="" disabled selected>Select Role</option>
            <option value="Student">Student</option>
            <option value="Faculty">Faculty</option>
          </select>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Name" name="n" required>
        </div>

        <div class="form-group">
          <input type="email" class="form-control" placeholder="Email Address" name="e" required>
        </div>

        <div class="form-group">
          <input type="text" class="form-control" placeholder="Mobile Number" name="mob" required>
        </div>

        <div id="facultyFields">
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Designation" name="des">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Name" name="course">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Code" name="course_code">
          </div>
        </div>

        <div class="form-group">
          <select name="pro" class="form-control" style="background-color:transparent;color:white" required>
            <option value="" disabled selected>Program</option>
            <option value="BCA">BCA</option>
            <option value="MCA">MCA</option>
            <option value="B.Tech">B.Tech</option>
            <option value="M.Tech">M.Tech</option>
          </select>
        </div>

        <div class="form-group">
          <select name="sem" class="form-control" style="background-color:transparent;color:white" required>
            <option value="" disabled selected>Semester</option>
            <option>i</option>
            <option>ii</option>
            <option>iii</option>
            <option>iv</option>
            <option>v</option>
            <option>vi</option>
            <option>vii</option>
            <option>viii</option>
          </select>
        </div>

        <div class="form-group">
          <select name="gen" class="form-control" style="background-color:transparent;color:white" required>
            <option value="" disabled selected>Gender</option>
            <option>Male</option>
            <option>Female</option>
          </select>
        </div>

        <div class="form-group">
          <input type="file" class="form-control" name="img" required>
        </div>

        <div class="form-group">
          <input type="date" class="form-control" name="yy" required>
        </div>

        <div class="form-group">
          <input type="password" class="form-control" placeholder="Password" name="p" required>
        </div>

        <input type="submit" value="Save" class="btn btn-info" name="save"/>
        <button type="reset" class="btn btn-warning">Reset</button>
      </form>
    </div>
  </div>
</div>

<script>
function toggleFields() {
  var role = document.getElementById('role').value;
  var facultyFields = document.getElementById('facultyFields');
  facultyFields.style.display = (role === 'Faculty') ? 'block' : 'none';
}
</script>

</body>
</html>
