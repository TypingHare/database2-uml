<?php

/**
 * This file serves as a page template file. You can copy this template to
 * your newly created file, remove the comments, and add elements in the
 * specific div.
 *
 * NOTE: Make sure to include a brief introduction to the file and change the
 * author name.
 *
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
    $course_id = $data['course_id'];
    $section_id = $data['section_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    create_ta($student_id, $course_id, $section_id, $semester, $year);

    redirect(Page::SELECT_PHD);
});

$student_id = $_GET['student_id'];
$student_name = get_student_by_id($student_id);
$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$select_section_url = build_url(Page::SELECT_TA_SECTION, [
    'student_id' => $student_id
]);

?>

<html lang="en">
<head>
  <title>Assign TA</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2>Confirm Selected TA and Section</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::ASSIGN_TA ?>"
      method="POST"
    >
        <div><b>Student Name: </b> <?= $student_name['name'] ?></div>

        <div><b>Student ID: </b> <?= $student_id ?></div>
        <input type="hidden" name="student_id"
            value="<?= $student_id ?>">

        <div><b>Course ID: </b> <?= $course_id ?></div>
        <input type="hidden" name="course_id"
            value="<?= $course_id ?>">

        <div><b>Section ID: </b> <?= $section_id ?></div>
        <input type="hidden" name="section_id"
            value="<?= $section_id ?>">

        <div><b>Semester: </b> <?= $semester ?></div>
        <input type="hidden" name="semester"
            value="<?= $semester ?>">

        <div><b>Year: </b> <?= $year ?></div>
        <input type="hidden" name="year"
            value="<?= $year ?>">

        <div style="display: flex; justify-content: center;">
            <button type="submit">Update</button>
            <a href="<?= $select_section_url ?>" style="margin-left: 0.5rem;">
                <button type="button">Cancel</button>
            </a>
      </div>
    </form>

    <!-- Add other elements. -->
  </div>
</div>

</body>
</html>
