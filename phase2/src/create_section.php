<?php

/**
 * This page allows admin to create a new section and appoint instructors to
 * teach each section.
 *
 * @author James Chen.
 */

include 'minimal.php';

/**
 * @api
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    create_new_section(
        $data['course_id'],
        "Section" . $data['section_id'],
        $data['semester'],
        $data['year'],
        $data['instructor_id'],
        $data['classroom_id'],
        $data['time_slot_id']
    );

    redirect(Page::SECTION);
});

$course_ids = array_column(get_all_courses(), 'course_id');
$instructors = get_all_instructors();
$classrooms = get_all_classrooms();
$time_slots = get_all_time_slots();

?>

<html lang="en">
<head>
  <title>Create Course Section</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h3>Create Course Section</h3>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::CREATE_SECTION ?>"
      method="POST"
    >
      <div>
        <label for="course_id">Course ID: </label>
        <select name="course_id" id="course_id">
            <?php foreach ($course_ids as $course_id): ?>
              <option value="<?= htmlspecialchars($course_id); ?>">
                  <?= htmlspecialchars($course_id); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="section_id">Section ID: </label>
        <input name="section_id" id="section_id" placeholder="example: 102" />
      </div>

      <div>
        <span>Semester: </span>
        <input type="radio" name="semester" id="semester-spring"
               value="Spring" />
        <label for="semester-spring">Spring</label>

        <input type="radio" name="semester" id="semester-fall" value="Fall"
               style="margin-left: 0.5rem;" />
        <label for="semester-fall">Fall</label>
      </div>

      <div>
        <label for="year">Year: </label>
        <input type="number" name="year" id="year" min="2000" max="2099"
               value="2025" />
      </div>

      <div>
        <label for="instructor">Instructor: </label>
        <select name="instructor_id" id="instructor">
            <?php foreach ($instructors as $instructor): ?>
              <option
                value="<?= htmlspecialchars($instructor['instructor_id']) ?>">
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
                value="<?= htmlspecialchars($classroom['classroom_id']) ?>">
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
                value="<?= htmlspecialchars($time_slot['time_slot_id']) ?>">
                  <?= htmlspecialchars(time_slot_to_string($time_slot)) ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <button type="submit">Submit</button>
        <a href="<?= Page::SECTION ?>" style="margin-left: 0.5rem;">
          <button type="button">Cancel</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
