<?php

/**
 * This page allows admin to update the information of a specific course
 * section.
 *
 * @param_get course_id The course ID.
 * @param_get section_id The section ID.
 * @param_get semester The semester.
 * @param_get year The year.
 * @author James Chen.
 */

require_once 'minimal.php';

handle(HttpMethod::POST, function ($data) {
    $course_id = $data['course_id'];
    $section_id = $data['section_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    $instructor_id = $data['instructor_id'];
    $classroom_id = $data['classroom_id'];
    $time_slot_id = $data['time_slot_id'];

    update_section(
        $course_id,
        $section_id,
        $semester,
        $year,
        $instructor_id,
        $classroom_id,
        $time_slot_id
    );
    success("Updated section successfully.");

    redirect(Page::SECTION);
});

$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$instructors = get_all_instructors();
$classrooms = get_all_classrooms();
$time_slots = get_all_time_slots();

$section = get_section($course_id, $section_id, $semester, $year);

?>

<html lang="en">
<head>
  <title>Edit Course Section</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h3>Edit Course Section</h3>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::EDIT_SECTION ?>"
      method="POST"
    >
      <div><b>Course ID: </b><?= htmlspecialchars($course_id) ?></div>
      <div><b>Section ID: </b><?= htmlspecialchars($section_id) ?></div>
      <div><b>Semester: </b><?= htmlspecialchars($semester) ?></div>
      <div><b>Year: </b><?= htmlspecialchars($year) ?></div>

      <input type="hidden" name="course_id"
             value="<?= htmlspecialchars($course_id) ?>">
      <input type="hidden" name="section_id"
             value="<?= htmlspecialchars($section_id) ?>">
      <input type="hidden" name="semester"
             value="<?= htmlspecialchars($semester) ?>">
      <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">

      <div>
        <label for="instructor">Instructor: </label>
        <select name="instructor_id" id="instructor">
            <?php foreach ($instructors as $instructor): ?>
              <option
                value="<?= htmlspecialchars($instructor['instructor_id']) ?>"
                  <?= $instructor['instructor_id'] === $section['instructor_id'] ? "selected" : "" ?>
              >
                  <?= htmlspecialchars($instructor['instructor_name']) ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="classroom">Classroom: </label>
        <select name="classroom_id" id="classroom">
            <?php foreach ($classrooms as $classroom): ?>
              <option
                value="<?= htmlspecialchars($classroom['classroom_id']) ?>"
                  <?= $classroom['classroom_id'] === $section['classroom_id'] ? "selected" : "" ?>
              >
                  <?= htmlspecialchars(classroom_to_string($classroom)) ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="time_slot">Time slot: </label>
        <select name="time_slot_id" id="time_slot">
            <?php foreach ($time_slots as $time_slot): ?>
              <option
                value="<?= htmlspecialchars($time_slot['time_slot_id']) ?>"
                  <?= $time_slot['time_slot_id'] === $section['time_slot_id'] ? "selected" : "" ?>
              >
                  <?= htmlspecialchars(time_slot_to_string($time_slot)) ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <button type="submit">Update</button>
        <a href="<?= Page::SECTION ?>" style="margin-left: 0.5rem">
          <button type="button">Cancel</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
