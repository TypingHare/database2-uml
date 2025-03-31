<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page allows admin to complete the action of assigning a grader to a section
 *
 * @param_get student_id The student ID.
 * @author Alexis Marx
 */

handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $course_id = $data['course_id'];
    $section_id = $data['section_id'];
    $semester = $data['semester'];
    $year = $data['year'];

    add_grader(
        $student_id,
        $course_id,
        $section_id,
        $semester,
        $year
    );

    redirect(Page::ADMIN);
});

$student_id = $_GET['student_id'];
$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$student = get_student_by_id($student_id);
$name = $student['name'];

$section = get_section_plus($course_id, $section_id, $semester, $year);

$back_url = build_url(Page::SELECT_GRADER, ['course_id' => $course_id, 'section_id' => $section_id, 'semester' => $semester, 'year' => $year]);

?>

<html lang="en">
<head>
  <title>Assign as grader</title>
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

<div style="display: flex; flex-direction: column; align-items: center;">
  <h2>Assign <?= $name ?> as grader for:</h2>
  <form
    style="display: flex; flex-direction: column; gap: 1rem;margin-top: 16vh;"
    action="assign_grader.php" method="POST">
    <input type="hidden" name="student_id" value="<?= $student_id ?>">
    <input type="hidden" name="course_id" value="<?= $section['course_id'] ?>">
    <input type="hidden" name="section_id"
           value="<?= $section['section_id'] ?>">
    <input type="hidden" name="semester" value="<?= $section['semester'] ?>">
    <input type="hidden" name="year" value="<?= $section['year'] ?>">
    <div><b>Course ID: </b> <?= $section['course_id'] ?></div>
    <div><b>Section ID: </b> <?= $section['section_id'] ?></div>
    <div><b>Semester: </b> <?= $section['semester'] ?> <?= $section['year'] ?>
    </div>
    <div><b>Instructor: </b> <?= $section['instructor_name'] ?></div>
    <div><b>Classroom: </b> <?= classroom_to_string($section) ?></div>
    <div><b>Time slot: </b> <?= time_slot_to_string($section) ?></div>
    <div style="display: flex; margin-top: 20px;">
      <button type="submit">Assign</button>
    </div>
  </form>
  <div style="display: flex; margin-top: 20px;">
    <a href="<?= $back_url ?>">
      <button>Back</button>
    </a>
  </div>
</div>

</body>
</html>
