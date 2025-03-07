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
