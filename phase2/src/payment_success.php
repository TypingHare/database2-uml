<?php

/**
 * This page serves as a payment successful notification page. Students will be
 * redirected to this page if they pay their tuition successfully. The student
 * will be redirected the student dashboard page to three seconds later.
 *
 * @param_get student_id The Student ID.
 * @param_get semester The semester of the bill.
 * @param_get year The year of the bill.
 * @param_get amount The amount that the student paid.
 * @author James Chen
 */

require_once 'minimal.php';

$student_id = $_GET['student_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];
$amount = $_GET['amount'];

$student = get_student_by_id($student_id);
$student_name = $student['name'];
$message = "Thank you, $student_name. Your payment of \$$amount for $semester $year tuition was successful!";

$student_url = build_url(Page::STUDENT, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>Payment Success Message</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2 style="color: green; font-weight: bold;"><?= $message ?></h2>
    <br />
    <div>
      The page will be redirected to the dashboard in 5 seconds...
    </div>
  </div>
</div>

<script>
    setTimeout(() => {
        location.href = "<?= $student_url ?>";
    }, 5000);
</script>

</body>
</html>
