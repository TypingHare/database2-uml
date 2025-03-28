<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page allows students to browse all sections that are offered in
 * the current semester
 *
 * @param_get student_id The student ID.
 * @author Alexis Marx
 */

// (date(""))

// $year = date("Y");

$student_id = $_GET['student_id'];
$sections = get_all_sections_semester_year("Fall", "2025");

function get_register_section_url(string $student_id,array $section): string
{
    return build_url(Page::REGISTER, [
        'student_id' => $student_id,
        'course_id' => $section['course_id'],
        'section_id' => $section['section_id'],
        'semester' => $section['semester'],
        'year' => $section['year'],
    ]);
}

$back_url = build_url(Page::STUDENT, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>Browse Courses</title>
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
    <h2>Browse Courses</h2>

    <table style="width:100%;">
      <tr>
        <td>Course ID</td>
        <td>Section ID</td>
        <td>Semester</td>
        <td>Year</td>
        <td>Instructor</td>
        <td>Classroom</td>
        <td>Time slot</td>
        <td style="color: grey;">Operation</td>
      </tr>
        <?php foreach ($sections as $section): ?>
          <tr>
            <td><?= $section['course_id'] ?></td>
            <td><?= $section['section_id'] ?></td>
            <td><?= $section['semester'] ?></td>
            <td><?= $section['year'] ?></td>
            <td><?= $section['instructor_name'] ?></td>
            <td><?= classroom_to_string($section) ?></td>
            <td><?= time_slot_to_string($section) ?></td>
            <td>
              <a href="<?= get_register_section_url($student_id,$section) ?>">
                <button>View</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
    </table>

    <div style="display: flex; justify-content: right;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
