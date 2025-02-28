<?php

namespace model;

use model\attribute\Column;
use model\attribute\Foreign;
use model\attribute\Primary;
use model\attribute\Type;

class Student
{
    #[Primary]
    #[Column(type: Type::VARCHAR, length: 10)]
    public string $studentId;

    #[Column(type: Type::VARCHAR, length: 20)]
    public string $name;

    #[Column(type: Type::VARCHAR, length: 50)]
    public string $email;

    #[Column(type: Type::VARCHAR, length: 100)]
    #[Foreign(table: 'department', column: 'dept_name')]
    public string $deptName;
}
