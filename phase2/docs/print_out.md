## Phase 2 Print Out

### 9. Next Semester Course Suggestion

```php
// suggested_courses.php
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    $selected_course_ids = $data['selected_course_ids'];

    foreach ($selected_course_ids as $selected_course_id) {
        [$course_id, $section_id] = explode(';', $selected_course_id);
        register_student($student_id, $course_id, $section_id, $semester, $year);
    }

    redirect(Page::COURSE_HISTORY, ['student_id' => $student_id]);
});

// service/degree_pathway.php
function get_suggested_courses(
    string $student_id,
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT section.*, course.course_name, instructor.instructor_name, take.grade
            FROM degree_pathway dp
            JOIN section ON section.course_id = dp.course_id
            JOIN course ON course.course_id = dp.course_id
            JOIN instructor ON instructor.instructor_id = section.instructor_id
            LEFT JOIN take ON take.course_id = dp.course_id
                           AND take.student_id = :student_id0
            WHERE dp.dept_name = (
                SELECT dept_name
                FROM student
                WHERE student_id = :student_id1
                LIMIT 1
            )
            AND NOT EXISTS (
                SELECT 1
                FROM prereq
                WHERE prereq.course_id = dp.course_id
                  AND NOT EXISTS (
                      SELECT 1
                      FROM take
                      WHERE take.student_id = :student_id2
                        AND take.course_id = prereq.prereq_id
                        AND (take.grade IS NOT NULL OR take.grade <> 'F')
                  )
            )
            AND NOT EXISTS (
                SELECT 1
                FROM take
                WHERE take.student_id = :student_id3
                  AND take.course_id = course.course_id
                  AND take.grade IS NOT NULL
                  AND take.grade <> 'F'
            )
            AND section.semester = :semester
            AND section.year = :year
            ORDER BY IF(take.grade = 'F', 0, 1)
        "
    );
    execute($stmt, [
        'student_id0' => $student_id,
        'student_id1' => $student_id,
        'student_id2' => $student_id,
        'student_id3' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->fetchAll();
}
```

### 10. Bill System