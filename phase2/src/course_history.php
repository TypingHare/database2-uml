<?php

/**
 * HTML template @author James Chen
 *
 * Clicking the "Access Records" button in student.php sends you to the student transcipt page
 * From here, the student will see all current and previous classes as well as a running total
 *of there credits and there cumcumulative gpa.
 * 
 * need to convert each letter grade into
 * A+ = 4 
 * A = 4
 * A- = 3.7
 * B+ = 3.3
 * B = 3
 * B- = 2.7
 * C+ = 2.3
 * C = 2
 * C- = 1.7
 * D+ = 1.3
 * D = 1
 * D- = 0.7
 * F = 0
 */

 function get_all_courses(): array // not 100% on how these functions should work
 {
     $stmt = pdo_instance()->prepare(   
      "SELECT course_name, credits 
       FROM course 
       WHERE course_id = (SELECT course_id 
                          FROM takes 
                          WHERE student_id = $student['student_id'])";     
     );
     execute($stmt);
 
     return $stmt->fetchAll();
 }


  function total_credits(int credits): int
 {
     $stmt = pdo_instance()->prepare(   
      "SELECT sum(credits) 
       FROM course 
       WHERE course_id = (SELECT course_id 
                          FROM takes 
                          WHERE student_id = :student_id);"
     );
     execute($stmt, ['student_id' => $student['id']]);
 
     return $stmt->fetchAll();// should I return just credits?
 }

/* need to convert each letter grade into grade points
 * A+ = 4 
 * A = 4
 * A- = 3.7 * B+ = 3.3
 * B = 3
 * B- = 2.7
 * C+ = 2.3
 * C = 2
 * C- = 1.7
 * D+ = 1.3
 * D = 1
 * D- = 0.7
 * F = 0 
 * Each class = grade points * class credit hours
 * sum all class point
 * sum all credit hours
 * cumulative gpa = (total grade points) / (total credit hours)
*/

function cumulative_gpa($student): array
{
    $stmt = pdo_instance()->prepare(   
     "SELECT grade 
      FROM takes 
      WHERE student_id = :student_id
      "
    ;
    execute($stmt, ['student_id' => $student['id']]);

    return $stmt->fetchAll();
}

?>

<html lang="en">
<head>
  <title>Student Transcript</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <!-- display all current and previous courses -->
    <!-- display total credits -->
    <!-- display cumulative GPA -->
  </div>
</div>

</body>
</html>

