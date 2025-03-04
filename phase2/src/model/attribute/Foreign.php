<?php

namespace model\attribute;

use Attribute;

/**
 * Represents a foreign key in the entity.
 *
 * @author James Chen
 */
#[Attribute]
class Foreign
{
    public function __construct(
        public string $table,
        public string $column
    ) {
    }
}
