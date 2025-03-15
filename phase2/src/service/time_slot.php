<?php

/**
 * Retrieves all time slots from the database.
 *
 * @return array An array containing all time slot records.
 * @author James Chen
 */
function get_all_time_slots(): array
{

    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM time_slot
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

/**
 * Retrieves a time slot record by its unique identifier.
 *
 * This function queries the database to fetch details of a specific time slot
 * based on the provided time slot ID.
 *
 * @param string $time_slot_id The unique identifier of the time slot.
 * @return array The time slot record as an associative array. Returns an empty
 *               array if no match is found.
 * @throws PDOException If a database error occurs during execution.
 */
function get_time_slot_by_id(string $time_slot_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM time_slot
            WHERE time_slot_id = :time_slot_id
        "
    );
    execute($stmt, ['time_slot_id' => $time_slot_id]);

    return $stmt->fetch();
}

/**
 * Converts a time slot array into a formatted string representation.
 *
 * @param array $time_slot An associative array containing 'day', 'start_time',
 *                         and 'end_time'.
 * @return string A formatted string representing the time slot.
 * @author James Chen
 */
function time_slot_to_string(array $time_slot): string
{
    return $time_slot['time_slot_id'] === null
        ? ''
        : $time_slot['day'] . ' ' . $time_slot['start_time'] . ' - ' . $time_slot['end_time'];
}

/**
 * Retrieves available time slots for a given semester and year.
 * A time slot is considered available if it has fewer than two assigned
 * sections.
 *
 * @param string $semester The semester for which to check availability.
 * @param string $year The year for which to check availability.
 * @return array An array of available time slots.
 * @author James Chen
 */
function get_available_time_slots(
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM time_slot
            WHERE time_slot_id NOT IN (
                SELECT time_slot.time_slot_id
                FROM time_slot
                LEFT JOIN section ON time_slot.time_slot_id = section.time_slot_id
                WHERE semester = :semester
                  AND year = :year
                GROUP BY time_slot.time_slot_id
                HAVING COUNT(*) >= 2
            )
        "
    );
    execute($stmt, ['semester' => $semester, 'year' => $year]);

    return $stmt->fetchAll();
}

function time_to_minutes(string $time): int
{
    list($hours, $minutes) = explode(':', $time);
    return (intval($hours) * 60) + intval($minutes);
}
