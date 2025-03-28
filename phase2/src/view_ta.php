<?php

require_once 'minimal.php';

function get_all_tas(): array // might move to student folder in sevices
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM TA
            ORDER BY semester, year;
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

$TAs = get_all_tas();
?>

<html lang="en">
<head>
  <title>Records</title>
  <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 0.5rem;
        }
    </style>

</head>
<body style="height: 100%;">

  <div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
  <h2>TA Records</h2>
    <div style="display: flex; flex-direction: column; gap: 1rem;">

      <table style="width:100%;">
        <tr>
            <td>Student ID</td>
            <td>Course ID</td>
            <td>Section ID</td>
            <td>Semester</td>
            <td>Year</td>
        </tr>
        <?php foreach ($TAs as $ta): ?>
        <tr>
            <td><?= $ta['student_id'] ?>
            </td>
            <td><?= $ta['course_id'] ?>
            </td>
            <td><?= $ta['section_id'] ?>
            </td>
            <td><?= $ta['semester'] ?>
            </td>
            <td><?= $ta['year'] ?>
            </td>
      </tr>
      <?php endforeach; ?>
  </table>

    <div style="display: flex; gap: 0.5rem;">
      <a href="<?= Page::ADMIN ?>">
        <button>Back</button>
      </a>
    </div>
    </div>

    <!-- Add other elements. -->
  </div>
</div>

</body>
</html>
