<?php

/**
 * This web page allows students to check their information and perform
 * operations.
 *
 * @param_get student_id The ID of the student.
 * @author James Chen
 */

require_once 'service/student.php';

if ($_GET['student_id'] == null) {
    error('Missing parameter "student_id"');
}

$student = get_student_by_id($_GET['student_id']);
if ($student === null) {
    error("Invalid student ID: " . $_GET['student_id']);
}

$student_type = get_student_type($student['student_id']);
$change_password_url = build_url(Page::CHANGE_PASSWORD, [
    'email' => $student['email']
])

?>

<html lang="en">
<head>
  <title>Student Dashboard</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <div><b>Student ID: </b> <?= $student['student_id'] ?></div>
    <div><b>Name: </b> <?= $student['name'] ?></div>
    <div><b>Email: </b> <?= $student['email'] ?></div>
    <div><b>Department: </b> <?= $student['dept_name'] ?></div>
    <div><b>Type: </b> <?= $student_type ?></div>

    <a href="<?= $change_password_url ?>">
      <button>Change Password</button>
    </a>
  </div>
</div>

</body>
</html>

