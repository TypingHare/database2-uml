<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all departments from the database.
 *
 * @return array An array of departments.
 * @author James Chen
 */
function get_all_departments(): array
{
    $stmt = pdo_prepare(
        "
            SELECT * FROM department
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}
