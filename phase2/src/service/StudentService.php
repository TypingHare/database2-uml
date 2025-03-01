<?php

namespace service;

use model\Student;
use function common\convert_records_to_models;
use function common\execute;
use function common\pdo_prepare;

class StudentService
{
    /**
     * Retrieves all students from the database.
     *
     * @return Student[] An array of Student objects.
     */
    public function getAllStudents(): array
    {
        $stmt = pdo_prepare("SELECT * FROM student");
        execute($stmt);

        return convert_records_to_models($stmt->fetchAll(), Student::class);
    }
}
