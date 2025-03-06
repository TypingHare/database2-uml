<?php

/**
 * This web page allows instructors to check their information and perform
 * operations.
 *
 * @author James Chen
 */

require_once 'api/service/instructor.php';

$instructor = get_instructor_by_id($_GET['instructor_id']);

?>

<html lang="en">
<head>
  <title>Instructor Dashboard</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 10rem;">
  <div>
    <p>
      <b>Instructor ID: </b> <?php echo($instructor['instructor_id']); ?>
    </p>
    <p>
      <b>Name: </b> <?php echo($instructor['instructor_name']); ?>
    </p>
    <p>
      <b>Email: </b> <?php echo($instructor['email']); ?>
    </p>
    <p>
      <b>Title: </b> <?php echo($instructor['title']); ?>
    </p>
    <p>
      <b>Department: </b> <?php echo($instructor['dept_name']); ?>
    </p>
  </div>
</div>

</body>
</html>

