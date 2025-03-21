<?php

/**
 * This page allows admin to create a bill for a student.
 *
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * @api
 * @param_post student_id The student ID.
 * @param_post semester The semester of the bill.
 * @param_post year The year of the bill.
 */
handle(HttpMethod::GET, function ($data) {
    if (!isset($data['action'])) {
        return;
    }

    $student_id = $data['student_id'];
    $semester = $data['semester'];
    $year = $data['year'];

    match ($_GET['action']) {
        'create_bill' => create_bill($student_id, $semester, $year),
        'reward' => create_scholarship($student_id, $semester, $year),
    };

    redirect(Page::BILLS, [
        'semester' => $semester,
        'year' => $year,
    ]);
});

$semester = $_GET['semester'];
$year = $_GET['year'];

$student_bills = get_students_and_bills($semester, $year);

function get_create_bill_url(array $student_bill): string
{
    return build_url(Page::BILLS, [
        'semester' => $student_bill['semester'],
        'year' => $student_bill['year'],
        'student_id' => $student_bill['student_id'],
        'action' => 'create_bill',
    ]);
}

function get_reward_url(array $student_bill): string
{
    return build_url(Page::BILLS, [
        'semester' => $student_bill['semester'],
        'year' => $student_bill['year'],
        'student_id' => $student_bill['student_id'],
        'action' => 'reward',
    ]);
}

?>

<html lang="en">
<head>
  <title>Bills</title>
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
    <h2>Bills</h2>

    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>Scholarship</th>
        <th style="font-weight: normal; color: gray;">Operation</th>
      </tr>

        <?php foreach ($student_bills as $record): ?>
          <tr>
            <td><?= $record['student_id'] ?></td>
            <td><?= $record['name'] ?></td>
            <td><?= $record['status'] ?></td>
            <td><?= '$' . $record['scholarship'] ?></td>
            <td>
              <a href="<?= get_create_bill_url($record) ?>"
                 style="text-decoration: none;">
                <button>Create</button>
              </a>
              <a href="<?= get_reward_url($record) ?>"
                 style="margin-left: 0.5rem;">
                <button>Reward</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
    </table>

    <div>
      <a href="<?= Page::ADMIN ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
