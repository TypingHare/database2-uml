<?php

require_once __DIR__ . '/../minimal.php';

function get_all_courses(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM course
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

function get_all_student_courses(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM take
            JOIN course ON course.course_id = take.course_id
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->fetchAll();
}

function get_total_credits(string $student_id): int
{
    $courses = get_all_student_courses($student_id);
    $credits_array = array_column($courses, 'credits');
    return array_sum(array_map('intval', $credits_array));
}

function convert_letter_grade_to_number(string $letter_grade): float
{
    return [
        'A+' => 4.0,
        'A' => 3.9,
        'A-' => 3.7,
        'B+' => 3.3,
        'B' => 3.0,
        'B-' => 2.7,
        'C+' => 2.3,
        'C' => 2.0,
        'C-' => 1.7,
        'D+' => 1.3,
        'D' => 1.0,
        'D-' => 0.7,
        'F' => 0.0
    ][$letter_grade];
}
