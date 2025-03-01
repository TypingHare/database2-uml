<?php

namespace common;

use Random\RandomException;
use RuntimeException;

/**
 * Generates a random string.
 *
 * @param int $length The length of the string to generate.
 * @return string A random string of the specified length.
 * @author James Chen
 */
function get_random_string(int $length): string
{
    try {
        return bin2hex(random_bytes($length / 2));
    } catch (RandomException $e) {
        throw new RuntimeException($e);
    }
}
