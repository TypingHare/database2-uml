<?php

require_once "../minimal.php";

/**
 * Creates a scholarship for a student and a specific semester and year.
 *
 * @param_post studentId The ID of the student to create the bill for.
 * @param_post semester The semester.
 * @param_post year The year.
 * @api
 * @author James Chen
 */
handle(HttpMethod::POST, function (array $data) {
    $studentId = require_field($data, "studentId");
    $semester = require_field($data, "semester");
    $year = require_field($data, "year");

    try {
        create_scholarship($studentId, $semester, $year);
        $student = get_student_by_id($studentId);
        $student_bill = get_student_bill($student, $semester, $year);
        success_response("Created a scholarship.", $student_bill);
    } catch (Exception) {
        error_response("Failed to create a scholarship for the student.");
    }
});
