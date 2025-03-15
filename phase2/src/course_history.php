<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * Clicking the "Access Records" button in student.php sends you to the student
 * transcript page From here, the student will see all current and previous
 * classes as well as a running total of their credits and there cumulative gpa.
 *
 * @author Victor Ruest
 */

/* need to convert each letter grade into grade points
 * A+ = 4
 * A = 3.9
 * A- = 3.7 * B+ = 3.3
 * B = 3
 * B- = 2.7
 * C+ = 2.3
 * C = 2
 * C- = 1.7
 * D+ = 1.3
 * D = 1
 * D- = 0.7
 * F = 0
 * Each class = grade points * class credit hours
 * sum all class point
 * sum all credit hours
 * cumulative gpa = (total grade points) / (total credit hours)
*/

function get_cumulative_gpa(string $student_id): float
{
    $completed_courses = get_all_completed_courses($student_id);
    //var_dump($completed_courses);
    $total_credits = array_sum(
        array_map('intval', array_column($completed_courses, 'credits'))
    );

    $grade_array = array_map(
        fn ($course) => convert_letter_grade_to_number($course['grade']) * intval($course['credits']),
        $completed_courses
    );
    $total_grade = array_sum($grade_array);

    return $total_credits === 0 ? 0. : $total_grade / $total_credits;
}

$student_id = $_GET['student_id'];
$courses = get_all_student_courses($student_id);
$total_credits = get_total_credits($student_id);
$cumulative_gpa = get_cumulative_gpa($student_id);

$finished_courses = get_all_completed_courses($student_id);

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
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <!-- display all current and previous courses -->
    <table style="width:100%;">
      <tr>
        <td>Course ID</td>
        <td>Course Name</td>
        <td>Credits</td>
        <td>Completed</td>
      </tr>
        <?php foreach ($finished_courses as $course): ?>
          <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= $course['course_name'] ?></td>
            <td><?= $course['credits'] ?></td>
            <td><?= $course['grade'] !== null ? 'Yes' : 'No' ?></td>
          </tr>
        <?php endforeach; ?>
    </table>

    <div><b>Total Credits: </b><?= $total_credits ?></div>
    <div><b>Cumulative GPA: </b><?= number_format($cumulative_gpa, 1) ?></div>
  </div>
</div>

</body>
</html>

