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
var_dump($advisees);

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
  <div>
    <h2>Advisees</h2>

    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <td>Student ID</td>
        <td>Student Name</td>
        <td>Advisor 1</td>
        <td>Advisor 2</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>

        <?php foreach ($advisees as $advisee): ?>
          <tr>
            <td><?=$advisee['student_id']?></td>
          </tr>
        <?php endforeach ?>
    </table>
  </div>
</div>

</body>
</html>
