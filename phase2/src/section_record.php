<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page contains a single section's current and past student records
 * @author Alexis Marx
 */


$instructor_id = $_GET['instructor_id'];
$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$instances = get_section_instances($instructor_id, $course_id, $section_id);
$back_url = build_url(Page::INSTRUCTOR_RECORDS, ['instructor_id' => $instructor_id]);

?>

<html lang="en">
<head>
  <title><?= $course_id ?></title>
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
    <h2><?= $course_id ?></h2>
    <h3><?= $section_id ?></h3>

      <?php foreach ($instances as $section): ?>
          <?php $records = get_section_records($course_id, $section_id, $section['semester'], $section['year']); ?>
        <h3><?= $section['semester'] ?> <?= $section['year'] ?></h3>
        <table style="width:100%;">
          <tr>
            <td>Name</td>
            <td>ID</td>
            <td>Grade</td>
          </tr>
            <?php foreach ($records as $record): ?>
                <?php $student = get_student_by_id($record['student_id']); ?>
                <?php $name = $student['name']; ?>
              <tr>
                <td><?= $name ?></td>
                <td><?= $record['student_id'] ?></td>
                <td><?= $record['grade'] ?></td>
              </tr>
            <?php endforeach; ?>
        </table>
      <?php endforeach; ?>
    <!--get data per instance -->


    <div style="display: flex; justify-content: right;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
