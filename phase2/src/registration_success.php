<?php

/**
 * @author Alexis Marx
 */

require_once 'minimal.php';

$student_id = $_GET['student_id'];
$course_id = $_GET['course_id'];
$section_id = $_GET['section_id'];
$semester = $_GET['semester'];
$year = $_GET['year'];

$message = "You have successfully enrolled in $course_id $section_id for $semester $year!";

$back_url = build_url(Page::BROWSE, ['student_id' => $student_id]);

?>

<html lang="en">
<head>
  <title>Registration Success</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2 style="color: green; font-weight: bold;"><?= $message ?></h2>
    <br />
  </div>
</div>

<div style="display: flex; justify-content: center;">
      <a href="<?= $back_url ?>">
        <button>Back</button>
      </a>
    </div>

</body>
</html>
