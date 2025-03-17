<?php

/**
 * This page allows the users to .
 *
 * @param_get student_id The ID of the PhD student to edit.
 * @param_get instructor_id The ID of the instructor editing this page.
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * @api
 * @param_post student_id The student ID.
 * @param_post instructor_id The ID of the instructor editing this page.
 * @param_post advisor1_instructor_id The ID of the first advisor.
 * @param_post advisor1_start_date The start date of the first advisor.
 * @param_post advisor1_end_date The end date of the first advisor.
 * @param_post
 */
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $instructor_id = $data['instructor_id'];
    $advisor1_instructor_id = $data['advisor1_instructor_id'];
    $advisor1_start_date = $data['advisor1_start_date'];
    $advisor1_end_date = $data['advisor1_end_date'];
    $advisor2_instructor_id = $data['advisor2_instructor_id'];
    $advisor2_start_date = $data['advisor2_start_date'];
    $advisor2_end_date = $data['advisor2_end_date'];

    if (!empty($advisor1_instructor_id)) {
        add_or_update_advisor(
            $student_id,
            $advisor1_instructor_id,
            $advisor1_start_date,
            $advisor1_end_date
        );
    }

    if (!empty($advisor2_instructor_id)) {
        add_or_update_advisor(
            $student_id,
            $advisor2_instructor_id,
            $advisor2_start_date,
            $advisor2_end_date
        );
    }

    remove_advisors_not($student_id, [
        $advisor1_instructor_id,
        $advisor2_instructor_id
    ]);

    redirect(Page::ADVISOR, ['instructor_id' => $instructor_id]);
});

$student_id = $_GET['student_id'];
$instructor_id = $_GET['instructor_id'] ?? '';

$phd_student = get_student_by_id($student_id);
$instructors = get_all_instructors();
$advisors = get_advisors($student_id);
$advisor1_instructor_id = $advisors[0]['instructor_id'] ?? "";
$advisor1_name = $advisors[0]['instructor_name'] ?? "";
$advisor1_start_date = $advisors[0]['start_date'] ?? "";
$advisor1_end_date = $advisors[0]['end_date'] ?? "";
$advisor2_instructor_id = $advisors[1]['instructor_id'] ?? "";
$advisor2_name = $advisors[1]['instructor_name'] ?? "";
$advisor2_start_date = $advisors[1]['start_date'] ?? "";
$advisor2_end_date = $advisors[1]['end_date'] ?? "";

$back_url = build_url(
    Page::ADVISOR,
    $instructor_id ? ['instructor_id' => $instructor_id] : []
);

?>

<html lang="en">
<head>
  <title>Edit PhD Students Advisors</title>
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
    <h2>Edit PhD Students Advisors</h2>

    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::EDIT_ADVISOR ?>" method="POST"
    >
      <div><b>Student ID: </b><?= $student_id ?></div>
      <div><b>Student Name: </b><?= $phd_student['name'] ?></div>

      <input type="hidden" name="student_id" value="<?= $student_id ?>">
      <input type="hidden" name="instructor_id" value="<?= $instructor_id ?>">

      <label>
        Advisor 1:
        <select name="advisor1_instructor_id">
          <option value="">(None)</option>
            <?php foreach ($instructors as $instructor): ?>
              <option value="<?= $instructor['instructor_id'] ?>"
                  <?= $advisor1_instructor_id == $instructor['instructor_id'] ? 'selected' : '' ?>
              >
                  <?= $instructor['instructor_name'] ?>
              </option>
            <?php endforeach; ?>
        </select>
      </label>

      <label>
        Advisor 1 Start Date:
        <input type="date" name="advisor1_start_date"
               value="<?= $advisor1_start_date ?>">
      </label>

      <label>
        Advisor 1 End Date:
        <input type="date" name="advisor1_end_date"
               value="<?= $advisor1_end_date ?>">
      </label>

      <label>
        Advisor 2:
        <select name="advisor2_instructor_id">
          <option value="">(None)</option>
            <?php foreach ($instructors as $instructor): ?>
              <option value="<?= $instructor['instructor_id'] ?>"
                  <?= $advisor2_instructor_id == $instructor['instructor_id'] ? 'selected' : '' ?>
              >
                  <?= $instructor['instructor_name'] ?>
              </option>
            <?php endforeach; ?>
        </select>
      </label>

      <label>
        Advisor 2 Start Date:
        <input type="date" name="advisor2_start_date"
               value="<?= $advisor2_start_date ?>">
      </label>

      <label>
        Advisor 2 End Date:
        <input type="date" name="advisor2_end_date"
               value="<?= $advisor2_end_date ?>">
      </label>

      <div style="display: flex; justify-content: center;">
        <button type="submit">Update</button>
        <a href="<?= $back_url ?>" style="margin-left: 0.5rem;">
          <button type="button">Back</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
