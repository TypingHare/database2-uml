<?php

namespace model\attribute;

use Attribute;

/**
 * Represents an entity.
 *
 * @author James Chen
 */
#[Attribute]
class Entity
{
    public function __construct(public string $name)
    {
    }
}
