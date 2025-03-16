<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all classrooms from the database.
 *
 * @return array An array of classrooms.
 * @author James Chen
 */
function get_all_classrooms(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM classroom
        "
    );
    $stmt->execute();

    return $stmt->fetchAll();
}

/**
 * Converts a classroom object into a string.
 *
 * @param array $classroom The classroom object.
 * @return string A string following to form of "<building> - <room_number>"
 * @example If $classroom['building'] is 'Dan' and $classroom['room_number'] is
 * '205', then the returned string would be 'Dan - 205'.
 */
function classroom_to_string(array $classroom): string
{
    return $classroom['building'] . ' ' . $classroom['room_number'];
}

function get_classroom_by_id(string $classroom_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM classroom
            WHERE classroom_id = :classroom_id
        "
    );

    $stmt->execute(['classroom_id' => $classroom_id]);
    return $stmt->fetch();
}
