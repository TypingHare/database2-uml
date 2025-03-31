<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page allows admin to select MS or UG students to be graders
 *
 * @author Alexis Marx
 */

$sections = get_grader_sections();
var_dump($sections);

function get_assign_url(string $course_id, string $section_id, string $semester, string $year): string
{
    return build_url(Page::SELECT_GRADER, [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year
    ]);
}

?>

<html lang="en">
<head>
  <title>Select section</title>
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
    <h2>Select Section to Assign Grader</h2>
    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <h3>Eligible Sections</h3>
        <td>Course ID</td>
        <td>Section ID</td>
        <td>Semester</td>
        <td>Year</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>
        <?php foreach ($sections as $section): ?>
          <tr>
            <td><?= $section['course_id'] ?></td>
            <td><?= $section['section_id'] ?></td>
            <td><?= $section['semester'] ?></td>
            <td><?= $section['year'] ?></td>
            <td>
              <a
                href="<?= get_assign_url($section['course_id'], $section['section_id'], $section['semester'], $section['year']) ?>">
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