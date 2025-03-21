<?php

require_once 'minimal.php';

/**
 * This page display a table of bills that are either paid or unpaid. Each
 * semester a student will receive a bill.
 *
 * @param_get student_id The student ID.
 * @author James Chen
 */

$student_id = $_GET['student_id'];
$bills = get_all_bills($student_id);

function get_bill_payment_url(string $student_id, array $bill): string
{
    return build_url(Page::BILL_PAYMENT, [
        'student_id' => $student_id,
        'semester' => $bill['semester'],
        'year' => $bill['year']
    ]);
}

$back_url = build_url(Page::STUDENT, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>My Bills</title>
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
    <h2>My Bills</h2>

    <table style="width:100%;">
      <tr style="font-weight: bold;">
        <th>Semester</th>
        <th>Year</th>
        <th>Status</th>
        <th style="color: grey; font-weight: normal">Operation</th>
      </tr>
        <?php foreach ($bills as $bill): ?>
          <tr>
            <td><?= $bill['semester'] ?></td>
            <td><?= $bill['year'] ?></td>
            <td
              style="color: <?= $bill['status'] === BillStatus::PAID ? 'green' : 'red' ?>"
            ><?= $bill['status'] ?></td>
            <td>
              <a href="<?= get_bill_payment_url($student_id, $bill) ?>">
                <button>View details</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
    </table>

    <div style="display: flex; justify-content: right;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
