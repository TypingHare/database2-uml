<?php

require_once 'minimal.php';

$php_students = get_all_phd();

//have to subtract all php students who are TAs(join of ta and php where student_id.ta = student_id.php) from get_all_php()
//for an accurate list of avaible TAs

function get_edit_url(string $student_id): string// go to url passing student id as query
{
    return build_url(Page::SELECT_TA_SECTION, [
        'student_id' => $student_id,
    ]);
}
?>

<html lang="en">
<head>
  <title>PhD Student selection</title>
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
    <h2>Students Selection</h2>
    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <td>Student ID</td>
        <td>Student Name</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>
        <?php foreach ($php_students as $student): ?>
          <tr>
            <td><?= $student['student_id'] ?></td>
            <td><?= $student['name'] ?></td>
            <td>
              <a href="<?= get_edit_url($student['student_id']) ?>"> 
                <button>Assign</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?> 
    </table>
    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= Page::ADMIN ?>">
            <button type="button">Back</button>
        </a>
    </div>
  </div>
</div>

</body>
</html>