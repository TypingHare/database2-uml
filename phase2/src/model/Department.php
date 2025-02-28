<?php

namespace model;

use model\attribute\Column;
use model\attribute\Type;

class Department
{
    #[Column(type: Type::VARCHAR, length: 100)]
    public string $deptName;

    #[Column(type: Type::VARCHAR, length: 100)]
    public string $location;
}
