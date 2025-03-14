<?php

require_once __DIR__ . '/../minimal.php';

function get_all_courses(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM course
        "
    );
    $stmt->execute();
    
    return $stmt->fetchAll();
}
