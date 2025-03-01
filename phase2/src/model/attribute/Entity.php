<?php

namespace model\attribute;

use Attribute;

#[Attribute]
class Entity
{
    public function __construct(public string $name)
    {
    }
}
