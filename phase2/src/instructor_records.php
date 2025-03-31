<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 *
 * This page allows students to browse all sections that are offered in
 * the current semester
 *
 * @author Alexis Marx
 */

$instructor_id = $_GET['instructor_id'];
$sections = get_all_sections_instructor($instructor_id);

function get_record_url(string $instructor_id, string $course_id, string $section_id): string
{
    return build_url(Page::RECORD, [
        'instructor_id' => $instructor_id,
        'course_id' => $course_id,
        'section_id' => $section_id,
    ]);
}

$back_url = build_url(Page::INSTRUCTOR, ['instructor_id' => $instructor_id]);

?>

<html lang="en">
<head>
  <title>Course Records</title>
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
    <h2>Course Records</h2>
      <?php foreach ($sections as $course_id => $course_sections): ?>
          <?php $this_course = $course_id ?>
        <h2><?= $course_id ?></h2>
        <table style="width:100%;">
          <tr>

            <td>Section ID</td>
            <td style="color: grey;">Operation</td>
          </tr>
            <?php foreach ($course_sections as $section): ?>
              <tr>
                <td><?= $section['section_id'] ?></td>
                <td>
                  <a
                    href="<?= get_record_url($instructor_id, $this_course, $section['section_id']) ?>">
                    <button>View Records</button>
                  </a>

                </td>

              </tr>
            <?php endforeach; ?>
        </table>

      <?php endforeach; ?>


    <div style="display: flex; justify-content: right;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>
  </div>
</div>

</body>
</html>
