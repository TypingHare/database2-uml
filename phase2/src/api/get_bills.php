<?php

require_once "../minimal.php";

/**
 * Retrieves all the students and their bills.
 *
 * This API returns a list of records like the following:
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
 * @param_get semester The semester to retrieve.
 * @param_get year The year to retrieve.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $semester = require_field($data, 'semester');
    $year = require_field($data, 'year');

    $students_and_bills = get_students_and_bills($semester, $year);
    success_response("Retrieved bills.", [
        'list' => $students_and_bills,
    ]);
});
