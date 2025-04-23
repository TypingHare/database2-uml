<?php

require_once "../minimal.php";

/**
 * Retrieves an instructor by ID.
 *
 * @param_get instructorId The ID of the instructor to retrieve.
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $instructorId = require_field($data, "instructorId");

    $instructor = get_instructor_by_id($instructorId);
    if (is_null($instructor)) {
        error_response("Instructor not found.");
    }

    success_response("Retrieved instructor.", $instructor);
});
