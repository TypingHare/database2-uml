<?php

/**
 * This web page allows admin to check their information and perform operations.
 *
 * @author James Chen
 */

require_once 'api/service/student.php';

$student = get_student_by_id($_GET['student_id']);

?>

<html lang="en">
<head>
    <title>Student INFO</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 10rem;">
    <div>
        <p>
            <b>Student ID: </b> <?php echo($student['student_id']); ?>
        </p>
        <p>
            <b>Name: </b> <?php echo($student['name']); ?>
        </p>
        <p>
            <b>Email: </b> <?php echo($student['email']); ?>
        </p>
        <p>
            <b>Department: </b> <?php echo($student['dept_name']); ?>
        </p>
    </div>
</div>

</body>
</html>

