<?php

require_once "../minimal.php";

/**
 * Attempts to register a student for a section.
 *
 * @api
 * @author Alexis Marx
 */
handle(HttpMethod::POST, function (array $data) {
    $studentId = require_field($data, 'studentId');
    $courseId = require_field($data, 'courseId');
    $sectionId = require_field($data, 'sectionId');
    $semester = "Fall";
    $year = "2025";

    try {
        register_student(
            $studentId,
            $courseId,
            $sectionId,
            $semester,
            $year
        );
    } catch (RuntimeException $e) {
        error_response($e->getMessage());
    }

    success_response("Successfully registered for section.", [
        'studentId' => $studentId,
        'courseId' => $courseId,
        'sectionId' => $sectionId
    ]);
});
