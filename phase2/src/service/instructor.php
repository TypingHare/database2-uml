<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches an instructor record by their email.
 *
 * @param string $email The instructor's email.
 * @return array|null An instructor object; null if no instructor is found.
 * @author James Chen
 */
function get_instructor_by_email(string $email): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM instructor 
            WHERE email = :email
        "
    );
    execute($stmt, ["email" => $email]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Fetches an instructor record by their ID.
 *
 * @param string $instructor_id The instructor ID.
 * @return array|null An instructor object; null if no instructor is found.
 * @author James Chen
 */
function get_instructor_by_id(string $instructor_id): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM instructor 
            WHERE instructor_id = :instructor_id
        "
    );
    execute($stmt, ["instructor_id" => $instructor_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Retrieves all instructors from the database.
 *
 * @return array An array containing all instructor records.
 */
function get_all_instructors(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM instructor
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

/**
 * Retrieves a list of available instructors for a given semester and year.
 *
 * This function queries the database to find instructors who are assigned to
 * fewer than two sections in the specified semester and year. It returns an
 * array of instructors who are available for additional assignments.
 *
 * @param string $semester The semester for which to check instructor
 *                         availability.
 * @param string $year The academic year associated with the semester.
 * @return array An array of available instructors. Each element represents an
 *               instructor's record.
 * @throws PDOException If there is a database error during the query execution.
 */
function get_available_instructors(
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM instructor
            WHERE instructor_id NOT IN (
                SELECT instructor.instructor_id FROM instructor
                LEFT JOIN section ON section.instructor_id = instructor.instructor_id
                WHERE semester = :semester
                  AND year = :year
                GROUP BY instructor.instructor_id
                HAVING COUNT(*) >= 2
            )
        "
    );
    execute($stmt, ['semester' => $semester, 'year' => $year]);

    return $stmt->fetchAll();
}
