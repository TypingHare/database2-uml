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

handle(HttpMethod::POST, function (array $data) {
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

    redirect(Page::REGISTER_SUCCESS, [
      'student_id' => $student_id,
      'course_id' => $course_id,
      'section_id' => $section_id,
      'semester' => $semester,
      'year' => $year,
  ]);
});

$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$section = get_section_plus($course_id, $section_id, $semester, $year);


$back_url = build_url(Page::BROWSE, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>Register</title>
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
  
    <form style="display: flex; flex-direction: column; gap: 1rem;margin-top: 16vh;" action="register.php" method="POST">
          <input type="hidden" name="student_id" value="<?= $student_id ?>">
          <input type="hidden" name="course_id" value="<?= $section['course_id'] ?>">
          <input type="hidden" name="section_id" value="<?= $section['section_id'] ?>">
          <input type="hidden" name="semester" value="<?= $section['semester'] ?>">
          <input type="hidden" name="year" value="<?= $section['year'] ?>">
      <div><b>Course ID: </b> <?= $section['course_id'] ?></div>
      <div><b>Section ID: </b> <?= $section['section_id'] ?></div>
      <div><b>Semester: </b> <?= $section['semester'] ?> <?= $section['year'] ?></div>
      <div><b>Instructor: </b> <?= $section['instructor_name'] ?></div>
      <div><b>Classroom: </b> <?= classroom_to_string($section) ?></div>
      <div><b>Time slot: </b> <?= time_slot_to_string($section) ?></div>
      <div style="display: flex; margin-top: 20px;"><button type = "submit">Register</button></div>
    </form>
      <div style="display: flex; margin-top: 20px;">
        <a href="<?= $back_url ?>">
          <button>Back</button>
        </a>
    </div>
</div>

</body>
</html>
