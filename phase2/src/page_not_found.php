<?php

/**
 * This web page serves as the destination of redirecting not-found pages.
 *
 * @author James Chen
 */

require_once 'minimal.php';

redirect('error.php', [
    'error_message' => 'Page not found: ' . strtok($_SERVER['REQUEST_URI'], '?')
]);
