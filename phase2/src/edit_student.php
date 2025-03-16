<?php

/**
 * This page allows students to edit their information.
 *
 * @param_get student_id The Student ID.
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * Update the information of a student. A student can update their name and
 * department.
 *
 * @api
 */
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $name = $data['name'];
    $dept_name = $data['dept_name'];
    update_student_info($student_id, $name, $dept_name);

    // PhD student
    if (get_student_type($student_id) === StudentType::PHD) {
        $proposal_defence_date = $data['proposal_defence_date'];
        $dissertation_defence_date = $data['dissertation_defence_date'];
        update_phd_info($student_id, $proposal_defence_date, $dissertation_defence_date);
    }
});

$student_id = $_GET['student_id'];
$student = get_student_by_id($student_id);
$student_type = get_student_type($student_id);
$student_subclass = get_student_subclass($student_id, $student_type);

$departments = get_all_departments();

?>

<html lang="en">
<head>
  <title>Edit Information</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <h3>Edit Information</h3>
    <div><b>Student ID: </b><?= $student['student_id'] ?></div>
    <div><b>Email: </b><?= $student['email'] ?></div>

    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::ERROR ?>"
      method="POST"
    >
      <label for="name">
        Student Name:
        <input name="name" value="<?= $student['name'] ?>" />
      </label>

      <label>
        Department:
        <select name="dept_name">
            <?php foreach ($departments as $department): ?>
              <option
                value="<?= htmlspecialchars($department['dept_name']); ?>">
                  <?= htmlspecialchars($department['dept_name']); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </label>

        <?php if ($student_type === StudentType::PHD): ?>
          <label>
            Proposal Defence Date:
            <input type="date" name="proposal_defence_date"
                   value="<?= $student['proposal_defence_date'] ?>" />
          </label>

          <label>
            Dissertation Defence Date:
            <input type="date" name="dissertation_defence_date"
                   value="<?= $student['dissertation_defence_date'] ?>">
          </label>
        <?php endif ?>
    </form>
  </div>
</div>

</body>
</html>
