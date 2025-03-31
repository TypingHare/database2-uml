<?php

require_once 'minimal.php';

/**
 * HTML template @author James Chen
 * 
 * This page allows admin to select MS or UG students to be graders
 *
 * @author Alexis Marx
 */

  function get_possible_graders(string $course_id, string $section_id, string $semester, string $year) : array 
  {
     $stmt = pdo_instance()->prepare(
        "
          SELECT DISTINCT a.student_id
          FROM (
              SELECT student_id FROM undergraduate
              UNION
              SELECT student_id FROM master
          ) AS a
          JOIN take t ON a.student_id = t.student_id
          WHERE t.course_id = :course_id 
            AND t.section_id = :section_id 
            AND t.semester = :semester 
            AND t.year = :year
            AND t.grade IN ('A-', 'A')
            AND NOT EXISTS (
                SELECT 1 
                FROM (
                    SELECT student_id FROM undergraduategrader
                    UNION
                    SELECT student_id FROM mastergrader
                ) as b
                WHERE b.student_id = a.student_id 
                AND b.semester = t.semester 
                AND b.year = t.year
            );

        "
    );
    
    execute($stmt, [
      "course_id" => $course_id,
      "section_id" => $section_id,
      "semester" => $semester,
      "year" => $year
  ]);

    return $stmt->fetchAll();
  }

  function get_assign_url(string $student_id, string $course_id, string $section_id, string $semester, string $year) : string {
    return build_url(Page::ASSIGN_GRADER, [
        'student_id' => $student_id,
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year
    ]);
 }

 $course_id = $_GET['course_id'];
 $section_id = $_GET['section_id'];
 $semester = $_GET['semester'];
 $year = $_GET['year'];
 $candidates = get_possible_graders($course_id, $section_id, $semester, $year);

 ?>

<html lang="en">
<head>
  <title>Select grader</title>
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
    <h2>Select a grader</h2>
    <table style="width: 100%;">
      <tr style="font-weight: bold;">
        <td>Student Name</td>
        <td>Student ID</td>
        <td>Degree Level</td>
        <td style="font-weight: normal; color: gray;">Operation</td>
      </tr>
        <?php foreach ($candidates as $student): 
          $s = get_student_by_id($student['student_id']);
          $name = $s['name'];
          $type = get_student_type($student['student_id']);
        ?>
          <tr>
            <td><?= $student['student_id'] ?></td>
            <td><?= $name ?></td>
            <td><?= $type ?></td>
            <td>
              <a href="<?= get_assign_url($student['student_id'], $section['course_id'], $section['section_id'], $section['semester'], $section['year']) ?>"> 
                <button>Assign</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?> 
    </table>

    <div style="display: flex; gap: 0.5rem;">
        <a href="<?= Page::SELECT_GRADER_SECTION ?>">
            <button type="button">Back</button>
        </a>
    </div>
  </div>
</div>

</body>
</html>


?>