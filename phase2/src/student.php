<?php

/**
 * This web page allows students to check their information and perform
 * operations.
 *
 * @param_get student_id The ID of the student.
 * @author James Chen
 * @author James Chen;
 */

require_once 'service/student.php';

if ($_GET['student_id'] == null) {
    error('Missing parameter "student_id"');
}

$student_id = $_GET['student_id'];
$student = get_student_by_id($student_id);
if ($student === null) {
    error("Invalid student ID: " . $student_id);
}
$student_type = get_student_type($student_id);
$student_subclass = get_student_subclass($student_id, $student_type);
$student_type = get_student_type($student_id);
$num_unpaid_bills = get_num_unpaid_bills($student_id);

$change_password_url = build_url(Page::CHANGE_PASSWORD, [
    'email' => $student['email']
]);
$edit_student_url = build_url(Page::EDIT_STUDENT, [
    'student_id' => $student_id
]);
$access_records_url = build_url(Page::COURSE_HISTORY, [
    'student_id' => $student_id
]);
$view_bills_url = build_url(Page::STUDENT_BILLS, [
    'student_id' => $student_id
]);
$suggested_courses_url = build_url(Page::SUGGESTED_COURSES, [
    'student_id' => $student_id,
    // To simplify, here we pass the Fall 2025 to the suggested_course page
    'semester' => 'Fall',
    'year' => '2025'
]);

$bill_correct_form = $num_unpaid_bills === 1 ? 'bill' : 'bills';
$unpaid_bills_message = "❗ HOLD: You have $num_unpaid_bills unpaid $bill_correct_form!";

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

      <?php if ($student_type === StudentType::PHD): ?>
        <div>
          <b>Qualifier: </b>
            <?= $student_subclass['qualifier'] ?>
        </div>
        <div>
          <b>Proposal Defence Date: </b>
            <?= $student_subclass['proposal_defence_date'] ?>
        </div>
        <div>
          <b>Dissertation Defence Date: </b>
            <?= $student_subclass['dissertation_defence_date'] ?>
        </div>
      <?php endif ?>

    <a href="<?= $change_password_url ?>">
      <button>Change password</button>
    </a>

    <a href="<?= $edit_student_url ?>">
      <button>Edit information</button>
    </a>

    <a href="<?= $access_records_url ?>">
      <button>Access records</button>
    </a>

    <a href="<?= $view_bills_url ?>">
      <button>View bills</button>
    </a>

    <a href="<?= $suggested_courses_url ?>">
      <button>Suggested courses</button>
    </a>

    <!-- Hold due to unpaid bills -->
    <div <?= $num_unpaid_bills > 0 ? '' : 'hidden' ?> style="color: red">
        <?= $unpaid_bills_message ?>
    </div>
  </div>
</div>

</body>
</html>
