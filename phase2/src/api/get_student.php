<?php

require_once "../minimal.php";

/**
 * Retrieves a student by ID.
 *
 * @param_get studentId The ID of the student to retrieve.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = require_field($data, "studentId");

    $student = get_student_by_id($studentId);
    if (is_null($student)) {
        error_response("Student not found.");
    }

    $student_type = get_student_type($student['student_id']);
    $subclass = get_student_subclass($studentId, $student_type);

    $student['student_type'] = $student_type;
    $student['subclass'] = $subclass;
    success_response("Retrieved student.", $student);
});
