<?php

/**
 * This file serves as the core of the Minimal framework for the server-side.
 */

// Common helper files
require_once __DIR__ . '/common/pdo.php';
require_once __DIR__ . '/common/constant.php';
require_once __DIR__ . '/common/response.php';
require_once __DIR__ . '/common/functional.php';

// Services; please register all service files here
$service_dir = __DIR__ . '/service';
foreach (scandir($service_dir) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require_once $service_dir . '/' . $file;
    }
}

/**
 * Handles API endpoint requests for specific HTTP methods.
 *
 * This function validates if the incoming request matches the specified HTTP
 * method, then executes the provided callback function with request data. GET
 * requests receive $_GET data, while other methods receive $_POST data. Any
 * exceptions thrown by the callback are caught and returned as error responses.
 *
 * @param string $method The HTTP method to respond to (e.g., "GET", "POST").
 * @param callable $callback The function to execute when method matches.
 *                           Receives request data array as its only parameter.
 * @return void
 * @example
 *
 *     handle(HttpMethod::GET, function ($data) {
 *         // Handle GET request with $data from $_GET
 *     });
 *
 * @author James Chen
 */
function handle(string $method, callable $callback): void
{
    if ($_SERVER["REQUEST_METHOD"] !== $method) {
        return;
    }

    try {
        $data = $method === HttpMethod::GET ? $_GET : $_POST;
        $callback($data);
    } catch (Exception $ex) {
        echo new ErrorResponse($ex->getMessage());
    }
}

/**
 * Redirects the user to a different URL by sending a Location header.
 *
 * @param string $url The destination URL path.
 * @param array $params The query parameters (or GET parameters).
 * @param string $prefix Optional prefix to prepend to the URL.
 * @return void This function does not return a value.
 * @author James Chen
 */
function redirect(string $url, array $params = [], string $prefix = '../'): void
{
    $query_string = $params ? '?' . http_build_query($params, '', '&'): '';

    header("Location: " . $prefix . $url . $query_string);
}

/**
 * Prints a success response.
 *
 * This function creates a new SuccessResponse object with the provided message
 * and status code, then outputs it directly to the response stream.
 *
 * @param string $message The success message to display to the user.
 * @param int $code HTTP status code for the response, defaults to 200 (OK).
 * @return void No return value, outputs directly to the response.
 * @see SuccessResponse Related class that handles formatting the response.
 * @author James Chen
 */
function success(string $message, int $code = 200): void
{
    echo new SuccessResponse($message, $code);
}
