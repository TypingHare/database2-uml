<?php

/**
 * This page allows students to pay a specific bills.
 *
 * @param_get student_id The student ID.
 * @param_get semester The semester of the bill.
 * @param_get year The year of the bill.
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * @api
 */
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    $amount = $data['amount'];
    // pay_bill($student_id, $semester, $year);

    success("Paid successfully!");
    redirect(Page::PAYMENT_SUCCESS, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year,
        'amount' => $amount,
    ]);
});

$student_id = $_GET['student_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];
$cancel_url = build_url(Page::STUDENT_BILLS, ['student_id' => $student_id]);

$sections = get_student_sections_by_semester($student_id, $semester, $year);
$total_credits = array_sum(array_column($sections, 'credits'));
$total_tuition = $total_credits * TUITION_PER_CREDIT;
$bill = get_bill($student_id, $semester, $year);
$paid = $bill['status'] === BillStatus::PAID;
$scholarship = get_scholarship($student_id, $semester, $year);
$scholarship = $scholarship === null ? 0 : $scholarship['scholarship'];
$amount = $total_tuition - $scholarship;

?>

<html lang="en">
<head>
  <title>Bill Payment</title>
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
  <div>
    <h2>Bill Payment</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::BILL_PAYMENT ?>"
      method="POST"
    >
      <div><b>Student ID: </b><?= $student_id ?></div>
      <div><b>Semester: </b><?= $semester ?></div>
      <div><b>Year: </b><?= $year ?></div>

      <input type="hidden" name="student_id" value="<?= $student_id ?>">
      <input type="hidden" name="semester" value="<?= $semester ?>">
      <input type="hidden" name="year" value="<?= $year ?>">
      <input type="hidden" name="total_tuition" value="<?= $total_tuition ?>">

      <table>
        <tr style="font-weight: bold;">
          <th>Course ID</th>
          <th>Course Name</th>
          <th>Credits</th>
          <th>Tuition</th>
        </tr>
          <?php foreach ($sections as $section): ?>
            <tr>
              <td><?= $section['course_id'] ?></td>
              <td><?= $section['course_name'] ?></td>
              <td><?= $section['credits'] ?></td>
              <td><?= $section['credits'] * TUITION_PER_CREDIT ?></td>
            </tr>
          <?php endforeach ?>
      </table>

      <div><b>Total Tuition: </b>$<?= $total_tuition ?></div>
      <div><b>Scholarship: </b>-$<?= $scholarship ?></div>
      <div><b>Amount: </b></div>
      <div>
        <b>Status: </b>
        <span style="color: <?= $paid ? 'green' : 'red' ?>">
          <?= $bill['status'] ?>
        </span>
      </div>

      <div style="display: flex; align-items: center; gap: 0.5rem;">
        <button type="submit" <?= $paid ? 'disabled' : '' ?>>
          Pay
        </button>
        <a href="<?= $cancel_url ?>">
          <button type="button">Cancel</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
