<?php

/**
 * This page allows admin to create a new section and appoint instructors to
 * teach each section.
 *
 * @author James Chen.
 */

include 'minimal.php';

$course_ids = array_column(get_all_courses(), 'course_id');

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
      action="<?= Page::CREATE_COURSE_SECTION ?>"
      method="POST"
    >
      <div>
        <label for="course_id">Department: </label>
        <select name="course_id" id="course_id">
            <?php foreach ($course_ids as $course_id): ?>
              <option value="<?= htmlspecialchars($course_id); ?>">
                  <?= htmlspecialchars($course_id); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>
    </form>
  </div>
</div>

</body>
</html>
