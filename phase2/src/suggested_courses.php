<?php

/**
 * This page displays a list of suggested courses and sections that students can
 * register.
 *
 * @param_get student_id The student ID.
 * @param_get semester The semester.
 * @param_get year The year.
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * @api
 */
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    $selected_course_ids = $data['selected_course_ids'];

    foreach ($selected_course_ids as $selected_course_id) {
        [$course_id, $section_id] = explode(';', $selected_course_id);
        take_section($student_id, $course_id, $section_id, $semester, $year);
    }

    redirect(Page::COURSE_HISTORY, ['student_id' => $student_id]);
});

$student_id = $_GET['student_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];
$suggested_courses = get_suggested_courses($student_id, $semester, $year);
foreach ($suggested_courses as &$suggested_course) {
    $suggested_course['id'] =
        $suggested_course['course_id'] . ';' . $suggested_course['section_id'];
}

$cancel_url = build_url(Page::STUDENT, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>Suggested Courses</title>
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
    <h2>Suggested Courses</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::SUGGESTED_COURSES ?>"
      method="POST"
    >
      <span>
        Please check the courses you want to sign up for, and click the "Confirm" button to continue.
      </span>
      <table>
        <tr style="font-weight: bold;">
          <th></th>
          <th>Course ID</th>
          <th>Course Name</th>
          <th>Section ID</th>
          <th>Instructor Name</th>
          <th>Course Grade</th>
        </tr>
          <?php foreach ($suggested_courses as $index => $record): ?>
            <tr>
              <td>
                <label>
                  <input type="checkbox" name="selected_course_ids[]"
                         value="<?= $record['id'] ?>" <?= $index < 3 ? 'checked' : '' ?>
                </label>
              </td>
              <td><?= $record['course_id'] ?></td>
              <td><?= $record['course_name'] ?></td>
              <td><?= $record['section_id'] ?></td>
              <td><?= $record['instructor_name'] ?></td>
              <td><?= $record['grade'] ?? "N/A" ?></td>
            </tr>
          <?php endforeach ?>
      </table>

      <input type="hidden" name="student_id" value="<?= $student_id ?>">
      <input type="hidden" name="semester" value="<?= $semester ?>">
      <input type="hidden" name="year" value="<?= $year ?>">

      <div style="display: flex; justify-content: right; gap: 0.5rem;">
        <button type="submit">Confirm</button>
        <a href="<?= $cancel_url ?>">
          <button type="button">Cancel</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
