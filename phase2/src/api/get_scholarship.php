<?php

require_once "../minimal.php";

/**
 * @param_get studentId The ID of the student.
 * @param_get semester The semester to retrieve.
 * @param_get year The year to retrieve.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = require_field($data, "studentId");
    $semester = require_field($data, 'semester');
    $year = require_field($data, 'year');

    $scholarship = get_scholarship($studentId, $semester, $year);
    if ($scholarship === null) {
        error_response("Scholarship record not found.");
    }

    success_response("Retrieved scholarship.", $scholarship);
});
