<?php

namespace model\attribute;

use Attribute;

/**
 * Represents a column.
 */
#[Attribute]
class Column
{
    public function __construct(
        public Type $type,
        public int  $length = 255
    ) {
    }
}
