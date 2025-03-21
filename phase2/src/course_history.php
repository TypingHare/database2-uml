<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * Clicking the "Access Records" button in student.php sends you to the student
 * transcript page From here, the student will see all current and previous
 * classes as well as a running total of their credits and there cumulative gpa.
 *
 * @author Victor Ruest; James Chen
 */

$student_id = $_GET['student_id'];
$courses = get_all_student_courses($student_id);
$total_credits = get_total_credits($student_id);
$cumulative_gpa = get_cumulative_gpa($student_id);

$current_courses = get_all_active_courses($student_id);
$finished_courses = get_all_completed_courses($student_id);

$student_url = build_url(Page::STUDENT, [
    'student_id' => $student_id
]);

?>

<html lang="en">
<head>
  <title>Student Transcript</title>

  <style>
      table, th, td {
          border: 1px solid black;
      }

      th, td {
          padding: 0.5rem;
      }
  </style>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <!-- display all current and previous courses -->
    <h2>Course History</h2>
    <h3> Current Courses</h3>
    <table style="width:100%;">
      <tr>
        <td>Course ID</td>
        <td>Course Name</td>
        <td>Credits</td>
        <td>Grade</td>
      </tr>
        <?php foreach ($current_courses as $course): ?>
          <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= $course['course_name'] ?></td>
            <td><?= $course['credits'] ?></td>
            <td><?= $course['grade'] !== null ? $course['grade'] : 'NA' ?></td>
          </tr>
        <?php endforeach; ?>
    </table>

    <h3> Completed Courses</h3>
    <table style="width:100%;">
      <tr>
        <td>Course ID</td>
        <td>Course Name</td>
        <td>Credits</td>
        <td>Grade</td>
      </tr>
        <?php foreach ($finished_courses as $course): ?>
          <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= $course['course_name'] ?></td>
            <td><?= $course['credits'] ?></td>
            <td><?= $course['grade'] ?></td>
          </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top: 1rem;"><b>Total Credits: </b><?= $total_credits ?>
    </div>
    <div style="margin-top: 1rem;"><b>Cumulative
        GPA: </b><?= number_format($cumulative_gpa, 1) ?>
    </div>

    <a href="<?= $student_url ?>">
      <button type="button" style="margin-top: 1rem;">Cancel</button>
    </a>
  </div>
</div>

</body>
</html>

