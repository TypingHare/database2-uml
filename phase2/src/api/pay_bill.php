<?php

require_once "../minimal.php";

/**
 * Student pays a bill.
 *
 * @param_post studentId The ID of the student.
 * @param_post semester The semester to retrieve.
 * @param_post year The year to retrieve.
 * @param_post amount The amount paid.
 * @api
 * @author James Chen
 */
handle(HttpMethod::POST, function (array $data) {
    $studentId = require_field($data, "studentId");
    $semester = require_field($data, "semester");
    $year = require_field($data, "year");

    pay_bill($studentId, $semester, $year);
    success_response("Paid bill successfully.", [
        'status' => BillStatus::PAID
    ]);
});
