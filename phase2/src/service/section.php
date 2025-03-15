<?php

require_once __DIR__ . '/../minimal.php';

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

/**
 * Checks if the given time slot is available for scheduling in the specified
 * semester and year.
 *
 * This function retrieves the list of available time slots for the provided
 * semester and year. If the specified time slot ID is not found in the
 * available time slots, it throws an exception, indicating that the time slot
 * has already been assigned to two or more sections.
 *
 * @param string $semester The semester for which availability is being checked.
 * @param string $year The academic year associated with the semester.
 * @param string $time_slot_id The unique identifier of the time slot to check.
 * @return void
 * @throws RuntimeException If the time slot is not available.
 *
 */
function check_time_slot(
    string $semester,
    string $year,
    string $time_slot_id
): void {
    $available_time_slots = get_available_time_slots($semester, $year);
    if (!in_array($time_slot_id, array_column($available_time_slots, 'time_slot_id'))) {
        throw new RuntimeException(
            "The time slot " .
            $time_slot_id .
            " has already been taken by two or more sections."
        );
    }
}

/**
 * Checks if the given instructor is available for teaching in the specified
 * semester and year.
 *
 * This function retrieves the list of available instructors for the provided
 * semester and year. If the specified instructor ID is not found in the
 * available instructors, it throws an exception, indicating that the instructor
 * is already assigned to two or more sections.
 *
 * @param string $semester The semester for which instructor availability is
 *                         being checked.
 * @param string $year The academic year associated with the semester.
 * @param string $instructor_id The unique identifier of the instructor to
 *                              check.
 * @return void
 * @throws RuntimeException If the instructor is not available for additional
 *                          sections.
 */
function check_instructor(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $available_instructors = get_available_instructors($semester, $year);
    if (!in_array($instructor_id, array_column($available_instructors, 'instructor_id'))) {
        throw new RuntimeException(
            "The instructor " .
            $instructor_id .
            " has two or more sections in this semester."
        );
    }
}

function check_classroom(
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT classroom.*
            FROM classroom
            JOIN section ON classroom.classroom_id = section.classroom_id
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

    if ($stmt->rowCount() === 0) {
        return;
    }

    $classroom = $stmt->fetch();
    if ($classroom['classroom_id'] !== $classroom_id) {
        throw new RuntimeException(
            "The instructor should take the two sections in the same classroom: " .
            $classroom['classroom_id']
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
 * @param string $time_slot_id The unique identifier of the new time slot being
 *                             assigned.
 * @throws RuntimeException If the instructor's time slots are not on the same
 *                          day or not consecutive.
 * @return void
 */
function check_instructor_time_slot(
    string $semester,
    string $year,
    string $instructor_id,
    string $time_slot_id,
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

    if ($stmt->rowCount() === 0) {
        return;
    }

    $ts1 = $stmt->fetch();
    $ts2 = get_time_slot_by_id($time_slot_id);

    // The two sections should be at the same days
    if ($ts1['day'] != $ts2['day']) {
        throw new RuntimeException('The instructor time slots should be at the same day.');
    }

    $ts1_start_minutes = time_to_minutes($ts1['start_time']);
    $ts1_end_minutes = time_to_minutes($ts1['end_time']);
    $ts2_start_minutes = time_to_minutes($ts2['start_time']);
    $ts2_end_minutes = time_to_minutes($ts2['end_time']);

    // The two sections cannot be the same
    if ($ts1_start_minutes === $ts2_start_minutes || $ts1_end_minutes === $ts2_end_minutes) {
        throw new RuntimeException('The instructor time slots are overlapped.');
    }

    // The two sections should be consecutive
    if (abs($ts1_start_minutes - $ts2_end_minutes) > 15 && abs($ts2_start_minutes - $ts1_end_minutes) > 15) {
        throw new RuntimeException('The instructor time slots are not consecutive.');
    }
}

/**
 * Creates a new section in the database.
 *
 * This function performs necessary validations before inserting a new section:
 * - Ensures the time slot is available.
 * - Checks if the instructor is available.
 * - Verifies that the instructor's sections are consecutive in time.
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
    check_time_slot($semester, $year, $time_slot_id);
    check_instructor($semester, $year, $instructor_id);
    check_classroom($semester, $year, $instructor_id, $classroom_id);
    check_instructor_time_slot($semester, $year, $instructor_id, $time_slot_id);

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

    return $data;
}

/**
 * Updates an existing section in the database.
 *
 * This function performs necessary validations before updating a section:
 * - Ensures the new time slot is available.
 * - Checks if the instructor is available for the new assignment.
 * - Verifies that the instructor's sections remain consecutive in time.
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
    check_time_slot($semester, $year, $time_slot_id);
    check_classroom($semester, $year, $instructor_id, $classroom_id);
    check_instructor_time_slot($semester, $year, $instructor_id, $time_slot_id);

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

    return $data;
}
