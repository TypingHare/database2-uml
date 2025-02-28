<?php

/**
 * This file includes some useful function programming style helper functions.
 *
 * @author James Chen
 */

/**
 * Applies a callback function to a given value and returns the value.
 *
 * @param mixed $it The value to be passed to the callback function.
 * @param callable $fn The function to apply, which takes $it as an argument.
 * @return mixed The original value after the function has been applied.
 */
function apply(mixed $it, callable $fn): mixed
{
    $fn($it);
    return $it;
}
