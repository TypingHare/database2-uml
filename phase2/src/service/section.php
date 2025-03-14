<?php

require_once __DIR__ . '/../minimal.php';

function create_new_section(
    string $course_id,
    string $section_id,
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
): array {
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO section (
                course_id, 
                section_id, 
                semester, 
                year, 
                instructor_id, 
                classroom_id
            ) VALUES (
                :course_id, 
                :section_id,
                :semester, 
                :year, 
                :instructor_id, 
                :classroom_id
            )
        "
    );
    $data = [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id,
        'classroom_id' => $classroom_id,
    ];
    $stmt->execute($data);

    return $data;
}
