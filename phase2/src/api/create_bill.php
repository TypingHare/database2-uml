<?php

require_once "../minimal.php";

/**
 * Creates a bill for a student and a specific semester and year.
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
        create_bill($studentId, $semester, $year);
        success_response("Created a bill.", [
            'studentId' => $studentId,
        ]);
    } catch (Exception) {
        error_response("Failed to create a bill for the student.");
    }
});
