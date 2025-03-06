<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all students from the database.
 *
 * @return array An array of departments.
 * @author James Chen
 */
function get_all_students(): array
{
    $stmt = pdo_prepare(
        "
            SELECT * FROM student 
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

/**
 * Fetches a student record by their email
 *
 * @param string $email The student's email.
 * @return array|null A student object; null if no student is found.
 * @author James Chen
 */
function get_student_by_email(string $email): array|null
{
    $stmt = pdo_prepare(
        "
            SELECT * FROM student
            WHERE email = :email
        "
    );
    execute($stmt, ["email" => $email]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Fetches a student record by their student ID.
 *
 * @param string $student_id The student ID.
 * @return array|null A student object; null if no student is found.
 * @author James Chen
 */
function get_student_by_id(string $student_id): array|null
{
    $stmt = pdo_prepare(
        "
            SELECT * FROM student
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ["student_id" => $student_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}
