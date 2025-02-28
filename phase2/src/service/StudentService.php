<?php

namespace service;

use model\Student;
use function common\connect_database;
use function common\convert_records_to_models;

class StudentService
{
    /**
     * Retrieves all students from the database.
     *
     * @return Student[] An array of Student objects.
     */
    public function getAllStudents(): array
    {
        $pdo = connect_database();
        $records = $pdo->query("SELECT * FROM student")->fetchAll();
        return convert_records_to_models($records, Student::class);
    }
}
