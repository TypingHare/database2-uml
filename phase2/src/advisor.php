<?php

/**
 * This page shows the list of PhD students and their two advisors.
 *
 * @param_get instructor_id The ID of the instructor editing this page.
 * @author James Chen
 */

require_once 'minimal.php';

$phd_and_advisors = get_phd_and_advisors();
$phd_having_advisors = array_keys($phd_and_advisors);
$phd_having_no_advisors = [];
foreach (get_all_phd() as $phd) {
    if (!in_array($phd['student_id'], $phd_having_advisors)) {
        $phd_having_no_advisors[] = $phd;
    }
}

$instructor_id = $_GET['instructor_id'] ?? '';
$back_url = $instructor_id ?
    build_url(Page::INSTRUCTOR, ['instructor_id' => $instructor_id]) :
    Page::ADMIN;

function get_edit_url(string $student_id): string
{
    return build_url(Page::EDIT_ADVISOR, [
        'student_id' => $student_id,
        'instructor_id' => $_GET['instructor_id'] ?? ''
    ]);
}

function get_advisor_desc(array|null $record): string
{
    if (!$record) {
        return '';
    }

    return "{$record['instructor_name']} <br/> ({$record['start_date']} to {$record['end_date']})";
}

?>

<html lang="en">
<head>
  <title>PhD Students Advisors</title>
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
    <h2>PhD Students Advisors</h2>
    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <td>Student ID</td>
        <td>Student Name</td>
        <td>Advisor 1</td>
        <td>Advisor 2</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>
        <?php foreach ($phd_and_advisors as $records): ?>
          <tr>
            <td><?= $records[0]['student_id'] ?></td>
            <td><?= $records[0]['name'] ?></td>
            <td><?= get_advisor_desc($records[0] ?? null) ?></td>
            <td><?= get_advisor_desc($records[1] ?? null) ?></td>
            <td>
              <a href="<?= get_edit_url($records[0]['student_id']) ?>">
                <button>Edit</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($phd_having_no_advisors as $phd): ?>
          <tr>
            <td><?= $phd['student_id'] ?></td>
            <td><?= $phd['name'] ?></td>
            <td></td>
            <td></td>
            <td>
              <a href="<?= get_edit_url($phd['student_id']) ?>">
                <button>Edit</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      <tr></tr>
    </table>

    <div style="display: flex; justify-content: center;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
