<?php

/**
 * This web page allows instructors to check their information and perform
 * operations.
 *
 * @param_get instructor_id The instructor ID.
 * @author James Chen
 */

require_once 'service/instructor.php';

$instructor_id = $_GET['instructor_id'];
if ($instructor_id == null) {
    error('Missing parameter "instructor_id"');
}


$instructor = get_instructor_by_id($instructor_id);
if ($instructor === null) {
    error("Invalid instructor ID: " . $instructor_id);
}


$change_password_url = build_url(Page::CHANGE_PASSWORD, [
    'email' => $instructor['email']
]);
$manage_advisor_url = build_url(Page::ADVISOR, [
    'instructor_id' => $instructor_id
]);
$view_advisees_url = build_url(Page::ADVISEE, [
    'instructor_id' => $instructor_id
]);

$view_records_url = build_url(Page::INSTRUCTOR_RECORDS, [
    'instructor_id' => $instructor_id
]);

?>

<html lang="en">
<head>
  <title>Instructor Dashboard</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <div><b>Instructor ID: </b> <?= $instructor['instructor_id'] ?></div>
    <div><b>Name: </b> <?= $instructor['instructor_name'] ?></div>
    <div><b>Email: </b> <?= $instructor['email'] ?></div>
    <div><b>Title: </b> <?= $instructor['title'] ?></div>
    <div><b>Department: </b> <?= $instructor['dept_name'] ?></div>

    <a href="<?= $change_password_url ?>">
      <button>Change Password</button>
    </a>

    <a
      href="<?= $manage_advisor_url ?>">
      <button>Manage Advisors</button>
    </a>

    <a
      href="<?= $view_advisees_url ?>">
      <button>View Advisees</button>
    </a>

    <a
      href="<?= $view_records_url ?>">
      <button>View Course Records</button>
    </a>
  </div>
</div>

</body>
</html>

