<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all sections from the database.
 *
 * @return array An array of sections.
 * @author James Chen
 */
function get_all_sections(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM section
            LEFT JOIN instructor ON section.instructor_id = instructor.instructor_id
            LEFT JOIN time_slot ON section.time_slot_id = time_slot.time_slot_id
            LEFT JOIN classroom ON section.classroom_id = classroom.classroom_id
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

/**
 * Fetches all sections from the database of a specific section and year.
 *
 * @author Alexis Marx
 */
function get_all_sections_semester_year(string $semester, string $year): array
{
    $data = [
        "semester" => $semester,
        "year" => $year,
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM section
            LEFT JOIN instructor ON section.instructor_id = instructor.instructor_id
            LEFT JOIN time_slot ON section.time_slot_id = time_slot.time_slot_id
            LEFT JOIN classroom ON section.classroom_id = classroom.classroom_id
            WHERE section.semester = :semester AND section.year = :year
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

function get_all_sections_instructor(string $instructor_id): array
{
    $data = [
        "instructor_id" => $instructor_id,
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT DISTINCT course_id, section_id from section
            WHERE section.instructor_id = :instructor_id
            GROUP BY course_id, section_id
        "
    );
    execute($stmt, $data);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sections[$row['course_id']][] = $row;
    }

    return $sections;
}

function get_section_instances(
    string $instructor_id,
    string $course_id,
    string $section_id,
): array {

    $data = [
        "instructor_id" => $instructor_id,
        "course_id" => $course_id,
        "section_id" => $section_id
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT semester, year from section
            WHERE section.instructor_id = :instructor_id AND 
            section.course_id = :course_id AND
            section.section_id = :section_id
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

function get_section_records(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
) : array {
    $data = [
        "course_id" => $course_id,
        "section_id" => $section_id,
        "semester" => $semester,
        "year" => $year
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT student_id, grade from take
            WHERE  take.course_id = :course_id AND
            take.section_id = :section_id AND
            take.semester = :semester AND
            take.year = :year
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

/**
 * Fetches a specific section from the database based on course ID, section ID,
 * semester, and year.
 *
 * @param string $course_id The ID of the course.
 * @param string $section_id The ID of the section.
 * @param string $semester The semester in which the section is offered.
 * @param string $year The academic year in which the section is offered.
 * @return array|null An associative array representing the section details if
 *                    found, or null if no matching section exists.
 * @author James Chen
 */
function get_section(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): array|null {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM section
            WHERE course_id = :course_id 
              AND section_id = :section_id
              AND semester = :semester 
              AND year = :year  
        "
    );
    execute($stmt, [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

function get_section_plus(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): array|null {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM section
            LEFT JOIN instructor ON section.instructor_id = instructor.instructor_id
            LEFT JOIN time_slot ON section.time_slot_id = time_slot.time_slot_id
            LEFT JOIN classroom ON section.classroom_id = classroom.classroom_id
            WHERE course_id = :course_id 
              AND section_id = :section_id
              AND semester = :semester 
              AND year = :year  
        "
    );
    execute($stmt, [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Checks if a given time slot in a specific semester and year has exceeded the
 * allowed number of sections. If the count exceeds the maximum allowed sections
 * per time slot, an exception is thrown.
 *
 * @param string $semester The semester to check.
 * @param string $year The academic year to check.
 * @param string $time_slot_id The ID of the time slot to verify.
 * @return void
 * @throws RuntimeException If the number of sections in the given time slot
 *                          exceeds the allowed limit.
 * @author James Chen
 */
function check_time_slot(
    string $semester,
    string $year,
    string $time_slot_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND time_slot_id = :time_slot_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'time_slot_id' => $time_slot_id
    ]);

    if ($stmt->fetch()['num_sections'] > 2) {
        $time_slot = get_time_slot_by_id($time_slot_id);
        throw new RuntimeException(
            "The time slot " .
            time_slot_to_string($time_slot) .
            " has already been taken by two or more sections."
        );
    }
}

/**
 * Ensures that an instructor is not assigned to multiple sections in the same
 * time slot during a specific semester and year. If the instructor is already
 * assigned to another section in the given time slot, an exception is thrown.
 *
 * @param string $semester The semester to check.
 * @param string $year The academic year to check.
 * @param string $instructor_id The ID of the instructor to verify.
 * @return void
 * @throws RuntimeException If the instructor is already assigned to another
 *                          section in the same time slot.
 * @author James Chen
 */
function check_instructor(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    if ($stmt->fetch()['num_sections'] > 2) {
        $instructor = get_instructor_by_id($instructor_id);
        throw new RuntimeException(
            "The instructor " .
            $instructor['instructor_name'] .
            " has two or more sections in this semester."
        );
    }
}

/**
 * Ensures that a classroom is available for a given time slot in a specific
 * semester and year. If the classroom is already assigned to another section
 * in the same time slot, an exception is thrown.
 *
 * @param string $semester The semester to check.
 * @param string $year The academic year to check.
 * @param string $classroom_id The ID of the classroom to verify.
 * @param string $time_slot_id The ID of the time slot to verify.
 * @return void
 * @throws RuntimeException If the classroom is already occupied in the given
 *                          time slot.
 * @author James Chen
 */
function check_classroom(
    string $semester,
    string $year,
    string $classroom_id,
    string $time_slot_id
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND classroom_id = :classroom_id
              AND time_slot_id = :time_slot_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'classroom_id' => $classroom_id,
        'time_slot_id' => $time_slot_id
    ]);

    if ($stmt->fetch()['num_sections'] > 1) {
        $classroom = get_classroom_by_id($classroom_id);
        $time_slot = get_time_slot_by_id($time_slot_id);
        throw new RuntimeException(
            "The classroom " .
            classroom_to_string($classroom) .
            " has two or more sections in the " .
            time_slot_to_string($time_slot) .
            " time slot."
        );
    }
}

/**
 * Ensures that an instructor is not assigned to multiple classrooms in the same
 * semester and year. If the instructor is already teaching in another
 * classroom, an exception is thrown.
 *
 * @param string $semester The semester to check.
 * @param string $year The academic year to check.
 * @param string $instructor_id The ID of the instructor to verify.
 * @return void
 * @throws RuntimeException If the instructor is assigned to multiple
 *                          classrooms.
 * @author James Chen
 */
function check_instructor_classroom(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    $classrooms = $stmt->fetchAll();
    $classroom_ids = array_column($classrooms, 'classroom_id');
    if (!empty($classroom_ids) && count(array_unique($classroom_ids)) != 1) {
        throw new RuntimeException(
            "The instructor should take the two sections in the same classroom!"
        );
    }
}

/**
 * Checks if an instructor has one or two sections scheduled in consecutive time
 * slots.
 *
 * This function verifies whether an instructor has existing sections in the
 * given semester and year. If the instructor already has a section, it checks
 * whether the new section's time slot is on the same day and within a 15-minute
 * gap, ensuring that time slots are consecutive.
 *
 * @param string $semester The semester for which the instructor's schedule is
 *                         being checked.
 * @param string $year The academic year associated with the semester.
 * @param string $instructor_id The unique identifier of the instructor whose
 *                              time slots are being checked.
 * @throws RuntimeException If the instructor's time slots are not on the same
 *                          day or not consecutive.
 * @return void
 */
function check_instructor_time_slot(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT time_slot.*
            FROM time_slot
            JOIN section ON time_slot.time_slot_id = section.time_slot_id
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    if ($stmt->rowCount() < 2) {
        return;
    }

    if ($stmt->rowCount() > 2) {
        throw new RuntimeException(
            "The instructor is taking more than two time sections."
        );
    }

    $ts1 = $stmt->fetch();
    $ts2 = $stmt->fetch();

    // The two sections should be at the same days
    if ($ts1['day'] != $ts2['day']) {
        throw new RuntimeException(
            "The instructor time slots must be at the same days."
        );
    }

    $ts1_start_minutes = time_to_minutes($ts1['start_time']);
    $ts1_end_minutes = time_to_minutes($ts1['end_time']);
    $ts2_start_minutes = time_to_minutes($ts2['start_time']);
    $ts2_end_minutes = time_to_minutes($ts2['end_time']);

    // The two sections cannot be the same
    if (
        $ts1_start_minutes === $ts2_start_minutes ||
        $ts1_end_minutes === $ts2_end_minutes
    ) {
        throw new RuntimeException(
            "The instructor time slots are not consecutive."
        );
    }

    // The two sections should be consecutive
    if (
        abs($ts1_start_minutes - $ts2_end_minutes) > 15 &&
        abs($ts2_start_minutes - $ts1_end_minutes) > 15
    ) {
        throw new RuntimeException(
            "The instructor time slots are not consecutive."
        );
    }
}

/**
 * Validates the legitimacy of a section based on various constraints.
 *
 * This function checks if an instructor, classroom, and time slot are valid
 * for a given semester and year. It ensures that the instructor and classroom
 * do not violate scheduling constraints.
 *
 * @param string $semester The semester for which the section is being checked.
 * @param string $year The academic year for which the section is being checked.
 * @param string $instructor_id The unique identifier of the instructor.
 * @param string $classroom_id The unique identifier of the classroom.
 * @param string $time_slot_id The unique identifier of the time slot.
 *
 * @throws RuntimeException If any of the validation checks fail.
 */
function check_section_legitimacy(
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
    string $time_slot_id,
): void {
    try {
        check_time_slot($semester, $year, $time_slot_id);
        check_instructor($semester, $year, $instructor_id);
        check_classroom($semester, $year, $classroom_id, $time_slot_id);
        check_instructor_classroom($semester, $year, $instructor_id);
        check_instructor_time_slot($semester, $year, $instructor_id);
    } catch (RuntimeException $ex) {
        pdo_instance()->rollBack();
        throw new RuntimeException($ex->getMessage());
    }
}

/**
 * Creates a new section in the database.
 *
 * Once validations pass, the section is inserted into the `section` table.
 *
 * @param string $course_id The unique identifier of the course.
 * @param string $section_id The unique identifier of the section.
 * @param string $semester The semester in which the section is offered.
 * @param string $year The academic year associated with the semester.
 * @param string $instructor_id The unique identifier of the instructor assigned
 *                              to the section.
 * @param string $classroom_id The unique identifier of the classroom assigned
 *                             to the section.
 * @param string $time_slot_id The unique identifier of the time slot for the
 *                             section.
 * @return array An associative array containing the section data that was
 *               inserted.
 * @throws RuntimeException If any of the validation checks fail.
 */
function create_new_section(
    string $course_id,
    string $section_id,
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
    string $time_slot_id,
): array {
    pdo_instance()->beginTransaction();
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO section (
                course_id, 
                section_id, 
                semester, 
                year, 
                instructor_id, 
                classroom_id,
                time_slot_id
            ) VALUES (
                :course_id, 
                :section_id,
                :semester, 
                :year, 
                :instructor_id, 
                :classroom_id,
                :time_slot_id
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
        'time_slot_id' => $time_slot_id,
    ];
    $stmt->execute($data);

    check_section_legitimacy($semester, $year, $instructor_id, $classroom_id, $time_slot_id);
    pdo_instance()->commit();

    return $data;
}

/**
 * Updates an existing section in the database.
 *
 * If all validations pass, the function updates the instructor, classroom, and
 * time slot for the specified section in the database.
 *
 * @param string $course_id The unique identifier of the course.
 * @param string $section_id The unique identifier of the section.
 * @param string $semester The semester in which the section is offered.
 * @param string $year The academic year associated with the semester.
 * @param string $instructor_id The unique identifier of the instructor assigned
 *                              to the section.
 * @param string $classroom_id The unique identifier of the classroom assigned
 *                             to the section.
 * @param string $time_slot_id The unique identifier of the time slot for the
 *                             section.
 *
 * @return array An associative array containing the updated section data.
 * @throws RuntimeException If any of the validation checks fail.
 */
function update_section(
    string $course_id,
    string $section_id,
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
    string $time_slot_id,
): array {
    pdo_instance()->beginTransaction();
    $stmt = pdo_instance()->prepare(
        "
            UPDATE section
            SET instructor_id = :instructor_id,
                classroom_id = :classroom_id,
                time_slot_id = :time_slot_id
            WHERE course_id = :course_id
              AND section_id = :section_id
              AND semester = :semester
              AND year = :year
        "
    );
    $data = [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id,
        'classroom_id' => $classroom_id,
        'time_slot_id' => $time_slot_id,
    ];
    $stmt->execute($data);

    check_section_legitimacy($semester, $year, $instructor_id, $classroom_id, $time_slot_id);
    pdo_instance()->commit();

    return $data;
}

function get_student_sections_by_semester(
    string $student_id,
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM take
            JOIN course ON take.course_id = course.course_id
            WHERE student_id = :student_id
              AND semester = :semester
              AND year = :year
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year,
    ]);

    return $stmt->fetchAll();
}

/**
 * Determines if section has space to be registered into
 *
 * @author Alexis Marx
 */

function check_section_availability(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): bool {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) AS seats_filled
            FROM take
            WHERE course_id = :course_id
              AND section_id = :section_id
              AND semester = :semester
              AND year = :year
        "
    );
    execute($stmt, [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year,
    ]);

    $count = $stmt->fetch();
    if ($count['seats_filled'] < 15) {
        return true;
    }
    return false;
}
