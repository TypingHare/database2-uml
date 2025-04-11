<?php

require_once "../minimal.php";

/**
 * Retrieves a student by ID.
 *
 * @param_get studentId The ID of the student to retrieve.
 * @api
 * @author James
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = $data["studentId"];

    $student = get_student_by_id($studentId);
    is_null($student) ?
        error_response("Student not found.") :
        success_response("Retrieved student.", $student);
});
