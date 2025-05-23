<?php

/**
 * This page allows an instructor to see all the advisees they have.
 *
 * @param_get instructor_id The ID of the instructor.
 * @author James Chen
 */

require_once 'minimal.php';



$instructor_id = $_GET['instructor_id'];
$advisees = get_all_advisees($instructor_id);

$back_url = build_url(Page::INSTRUCTOR, [
    'instructor_id' => $instructor_id
]);

// go to url passing student id and instructor id as query(needed for back button on student page)
function get_student_url(string $student_id, string $instructor_id): string
{
    return build_url(Page::STUDENT, [
        'student_id' => $student_id,
        'instructor_id' => $instructor_id
    ]);
}



?>

<html lang="en">
<head>
  <title>Advisees</title>
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
    <h2>Advisees</h2>

    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <td>Student ID</td>
        <td>Student Name</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>

        <?php foreach ($advisees as $advisee): ?>
          <tr>
            <td><?= $advisee['student_id'] ?></td>
            <td><?= $advisee['name'] ?></td>
            <td><?= $advisee['start_date'] ?></td>
            <td><?= $advisee['end_date'] ?></td>
            <td>
              <a href="<?= get_student_url($advisee['student_id'], $instructor_id) ?>"> 
                <button>Access</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
    </table>

    <div>
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
