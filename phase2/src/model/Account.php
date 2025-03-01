<?php

namespace model;

use model\attribute\Column;
use model\attribute\Entity;
use model\attribute\Primary;
use model\attribute\Type;

#[Entity('account')]
class Account
{
    #[Primary]
    #[Column(type: Type::VARCHAR, length: 50)]
    public string $email;

    #[Column(type: Type::VARCHAR, length: 20)]
    public string $password;

    #[Column(type: Type::VARCHAR, length: 20)]
    public string $type;
}
