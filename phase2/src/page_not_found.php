<?php

/**
 * This web page serves as the destination of redirecting not-found pages.
 *
 * @author James Chen
 */

echo json_encode([
    'status' => 'error',
    'message' => 'Page or Api not found: ' . $_SERVER['REQUEST_URI']
]);
