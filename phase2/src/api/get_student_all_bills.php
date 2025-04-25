<?php

include_once "../minimal.php";

/**
 * Retrieves all bills of a specified student.
 *
 * @param_get studentId The ID of the student.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = require_field($data, "studentId");

    $bills = get_all_bills($studentId);
    success_response("Retrieved all bills.", [
        'list' => $bills
    ]);
});
