<?php

namespace test;

/**
 * Demonstrates JSON encoding of a PHP array using the `json_encode()` function.
 *
 * When executed, this script outputs the following JSON-formatted string:
 *
 *     {"message":"Hello World!","data":{"course_id":"COMP1010","course_name":"Computing I","credits":3}}
 *
 * Purpose: Illustrates how server-side data structures can be serialized for
 * client-side consumption. The client application will parse this JSON string
 * back into a native data structure in its respective programming language.
 *
 * @author James Chen
 * @see https://www.php.net/manual/en/function.json-encode.php
 */

echo json_encode([
    'message' => 'Hello World!',
    'data' => [
        'course_id' => 'COMP1010',
        'course_name' => 'Computing I',
        'credits' => 3
    ]
]);
