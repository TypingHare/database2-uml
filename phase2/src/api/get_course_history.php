<?php

require_once "../minimal.php";

/**
 * Retrieves all the completed courses and active courses.
 *
 * @param_get studentId The ID of the student to retrieve.
 * @api
 * @author Victor Ruest
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId = require_field($data, "studentId");

    $student = get_student_by_id($studentId);
    if (is_null($student)) {
        error_response("Student not found.");
    }

    $completedCourses = get_all_completed_courses($studentId);
    $currentCourses = get_all_active_courses($studentId);

    success_response("Retrieved all current courses.", [
        "current_list" => array_values($currentCourses),
        "completed_list" => array_values($completedCourses)
    ]);
});
