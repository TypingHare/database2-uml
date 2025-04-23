<?php

/**
 * This web page serves as the destination of redirecting not-found pages.
 *
 * @author James Chen
 */

require_once 'minimal.php';

redirect_to_error_page('Page not found: ' . strtok($_SERVER['REQUEST_URI'], '?'));
