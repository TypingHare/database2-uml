<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page allows students to attempt to register for the selected section
 *
 * @param_get student_id The student ID.
 * @author Alexis Marx
 */

$student_id = $_GET['student_id'];
$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$section = get_section($course_id, $section_id, $semester, $year);

handle(HttpMethod::POST, function ($data) {
    $student_id = $data['student_id'];
    $course_id = $data['course_id'];
    $section_id = $data['section_id'];
    $semester = $data['semester'];
    $year = $data['year'];

    register_student(
        $student_id,
        $course_id,
        $section_id,
        $semester,
        $year
    );

    redirect(Page::REGISTER);
});

$back_url = build_url(Page::STUDENT, ['student_id' => $student_id]);

// TODO: I don't understand the HTML portion: Why should we display a table of
// sections in this page? -- James Chen
$sections = []

?>

<html lang="en">
<head>
  <title>Registration Status</title>
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
    <h2>Register for Section</h2>

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
              <a href="<?= get_edit_section_url($section) ?>">
                <button type="submit">Register</button>
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
