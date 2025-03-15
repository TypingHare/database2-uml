<?php

/**
 * This page displays all the sections.
 *
 * @author James Chen
 */

require_once 'minimal.php';

$sections = get_all_sections();
usort(
    $sections,
    fn ($a, $b) => [$a['year'], $a['semester']] <=> [$b['year'], $b['semester']]
);

function get_edit_section_url(array $section): string
{
    return build_url(Page::EDIT_SECTION, [
        'course_id' => $section['course_id'],
        'section_id' => $section['section_id'],
        'semester' => $section['semester'],
        'year' => $section['year'],
    ]);
}

?>

<html lang="en">
<head>
  <title>Sections</title>
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
    <h3>Sections</h3>
    <table style="width:100%;">
      <tr>
        <td>Course ID</td>
        <td>Section ID</td>
        <td>Semester</td>
        <td>Year</td>
        <td>Instructor</td>
        <td>Classroom</td>
        <td>Time slot</td>
        <td style="color: grey;">Operation</td>
      </tr>
        <?php foreach ($sections as $section): ?>
          <tr>
            <td><?= $section['course_id'] ?></td>
            <td><?= $section['section_id'] ?></td>
            <td><?= $section['semester'] ?></td>
            <td><?= $section['year'] ?></td>
            <td><?= $section['instructor_name'] ?></td>
            <td><?= classroom_to_string($section) ?></td>
            <td><?= time_slot_to_string($section) ?></td>
            <td>
              <a href="<?= get_edit_section_url($section) ?>">
                <button>Edit</button>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
    </table>

    <div style="margin-top: 1rem;">
      <a href="<?= Page::CREATE_SECTION ?>">
        <button>Create section</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
