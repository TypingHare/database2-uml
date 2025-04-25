<?php

require_once "../minimal.php";

/*
Instructors have access to records of all course sections they have taught, including
names of current semester's enrolled students and the names and grades of students
from past semesters.
*/

/**
 * Retrieves all the sections taught by the instructor current/previous.
 *
 * @param_get instructorId The ID of the instructor to retrieve.
 * @api
 * @author Victor Ruest
 */
handle(HttpMethod::GET, function (array $data) {
    $instructorId = require_field($data, "instructor_id");

    $instructor = get_instructor_by_id($instructorId);
    if (is_null($instructor)) {
        error_response("Instructors not found.");
    }

    $detailedSectionRecords = [];

    $sections = get_all_sections_instructor($instructor["instructor_id"]);

    foreach ($sections as $courseSections) {
        foreach ($courseSections as $section) {
            $courseId = $section["course_id"];
            $sectionId = $section["section_id"];

            $instances = get_section_instances($instructor["instructor_id"], $courseId, $sectionId);
            $detailedInstances = [];

            foreach ($instances as $instance) {
                $semester = $instance["semester"];
                $year = $instance["year"];
                $studenInfo = get_section_records($courseId, $sectionId, $semester, $year);
                $students = [];

                foreach ($studenInfo as $student) {
                    $studentId = $student["student_id"];
                    $studentRecord = get_student_by_id($studentId);
                    // combination ternart operator(?:) and null coalescing operator(??)
                    // checks if there is a record first else null
                    // if student record exist, does the student have a name else assign null
                    $name = $studentRecord ? $studentRecord["name"] ?? null : null;
                    $grade = $student["grade"];

                    $students[] = [
                        "studentId" => $studentId,
                        "name" => $name,
                        "grade" => $grade
                    ];
                }

                $detailedInstances[] = [
                    "semester" => $semester,
                    "year" => $year,
                    "students" => $students
                ];
            }
            $detailedSectionRecords[] = [
                "course_id" => $courseId,
                "section_id" => $sectionId,
                "sections" => $detailedInstances
            ];
        }
    }
    success_response("Retrieved all sections.", [
        "instructor_sections" => array_values($detailedSectionRecords)
    ]);
});
