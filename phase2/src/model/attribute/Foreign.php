<?php

namespace model\attribute;

use Attribute;

#[Attribute]
class Foreign
{
    public function __construct(
        public string $table,
        public string $column
    ) {
    }
}
