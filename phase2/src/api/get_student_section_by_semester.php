<?php

include_once "../minimal.php";

/**
 * Retrieves all sections by a specific student ID, semester, and year.
 *
 * @param_get studentId The ID of the student.
 * @param_get semester The semester.
 * @param_get year The year.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = require_field($data, "studentId");
    $semester = require_field($data, "semester");
    $year = require_field($data, "year");

    $sections = get_student_sections_by_semester($studentId, $semester, $year);
    success_response("Retrieved all all sections.", [
        'sections' => $sections
    ]);
});
