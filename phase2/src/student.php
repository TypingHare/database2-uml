<?php

/**
 * This web page allows students to check their information and perform
 * operations.
 *
 * @author James Chen
 */

require_once 'service/student.php';

$student = get_student_by_id($_GET['student_id']);
handle(HttpMethod::GET, function () use ($student) {
    if ($student === null) {
        throw new RuntimeException("Invalid student ID: " . $_GET['student_id']);
    }
});

$student_type = get_student_type($student['student_id']);

?>

<html lang="en">
<head>
  <title>Student Dashboard</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <p><b>Student ID: </b> <?php echo($student['student_id']); ?></p>
    <p><b>Name: </b> <?php echo($student['name']); ?></p>
    <p><b>Email: </b> <?php echo($student['email']); ?></p>
    <p><b>Department: </b> <?php echo $student['dept_name']; ?></p>
    <p><b>Type: </b> <?php echo $student_type; ?></p>

    <button onclick="window.location.href='change_password.php';">
      Change Password
    </button>
  </div>
</div>

</body>
</html>

