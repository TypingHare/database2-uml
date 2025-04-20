<?php

require_once "../minimal.php";

/**
 * Retrieves all the departments.
 *
 * @api
 * @author James Chen
 */
handle(HttpMethod::GET, function (array $data) {
    $studentId =  require_field($data, "studentId");

    $student = get_student_by_id($studentId);
    if (is_null($student)) {
        error_response("Student not found.");
    }

    $completedCourses = get_all_completed_courses($studentId);
    $currentCourses = get_all_active_courses($studentId);

    success_response("Retrieved all current courses.", [
        "current_list" => $currentCourses,
        "completed_list" => $completedCourses
    ]);



});
