<?php

require_once __DIR__ . '/../minimal.php';

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
