<?php

require_once "../minimal.php";

/**
 * Retrieves a student and their bill and scholarship.
 *
 * This API returns a record like the following:
 *
 *     {
 *         "studentId": "0102559623",
 *         "name": "Steve Rogers",
 *         "email": "avengersassemble@stark.com",
 *         "deptName": "Miner School of Computer & Information Sciences",
 *         "semester": "Fall",
 *         "year": "2025",
 *         "status": "Not Created",
 *         "scholarship": 0
 *     }
 *
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

    $student = get_student_by_id($studentId);
    if ($student === null) {
        error_response("Student not found.");
    }

    $student_bill = get_student_bill($student, $semester, $year);
    $scholarship = get_scholarship($studentId, $semester, $year);
    $cumulative_gpa = get_cumulative_gpa($studentId);
    $student_bill['has_scholarship'] = $scholarship !== null;
    $student_bill['cumulative_gpa'] = $cumulative_gpa;

    success_response("Retrieved student bill.", $student_bill);
});
