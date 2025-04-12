<?php

/** @noinspection PhpNoReturnAttributeCanBeAddedInspection */

/**
 * This file serves as the core of the Minimal framework.
 *
 * @example
 *
 *     require_once 'minimal.php';
 *
 * @author James Chen
 */

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Common helper files
require_once __DIR__ . '/common/pdo.php';
require_once __DIR__ . '/common/constant.php';
require_once __DIR__ . '/common/response.php';

// Services; please register all service files here
$service_dir = __DIR__ . '/service';
foreach (scandir($service_dir) as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        require_once $service_dir . '/' . $file;
    }
}

register_shutdown_function(function () {
    $pdo = pdo_instance(true);
    if ($pdo !== null && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
});

/**
 * Handles API endpoint requests for specific HTTP methods.
 *
 * This function validates if the incoming request matches the specified HTTP
 * method, then executes the provided callback function with request data. GET
 * requests receive $_GET data, while other methods receive $_POST data. Any
 * exceptions thrown by the callback are caught and returned as error responses.
 *
 * It does nothing if the HTTP method is not equal to the given one.
 *
 * @param string $method The HTTP method to respond to (e.g., "GET", "POST").
 * @param callable $callback The function to execute when method matches.
 *                           Receives request data array as its only parameter.
 * @return void
 * @see ErrorResponse
 * @example
 *
 *     handle(HttpMethod::GET, function ($data) {
 *         // Handle GET request with $data from $_GET
 *     });
 *
 * @example
 *
 *     handle(HttpMethod::POST, function ($data) {
 *          // Handle POST request with $data from $_POST
 *      });
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
        $error_message = $ex->getMessage();
        echo new ErrorResponse($error_message);
        redirect_to_error_page($error_message);
    }
}

/**
 * Builds a URL with query parameters.
 *
 * This function takes a base URL and an optional associative array of query
 * parameters, then appends them as a query string to the URL.
 *
 * @param string $url The base URL.
 * @param array $params Optional associative array of query parameters.
 * @return string The constructed URL with query parameters.
 * @author James Chen
 */
function build_url(string $url, array $params = []): string
{
    $query_string = $params ? '?' . http_build_query($params, '', '&') : '';
    return $url . $query_string;
}

/**
 * Redirects the user to a different URL by sending a Location header.
 *
 * This function sets the `Location` header to a given URL. When the browser
 * receives the response, it will immediately redirect the user to that URL.
 *
 * This function will immediately exit the PHP program. In other words, any code
 * after this function will be skipped.
 *
 * @param string $url The destination URL path.
 * @param array $params The query parameters (or GET parameters).
 * @param string $prefix Optional prefix to prepend to the URL.
 * @return void
 * @author James Chen
 */
function redirect(
    string $url,
    array  $params = [],
    string $prefix = ''
): void {
    header("Location: " . $prefix . build_url($url, $params));
    exit(0);
}

/**
 * Redirects the user to a specified error page with an error message.
 *
 * This function calls `redirect()` to navigate to the given error page
 * while passing the error message as a parameter.
 *
 * @param string $error_message The error message to be displayed on the error
 *                              page.
 * @param string $error_page The URL or filename of the error page.
 * @return void
 * @author James Chen
 */

function redirect_to_error_page(
    string $error_message,
    string $error_page = Page::ERROR
): void {
    redirect($error_page, ['error_message' => $error_message]);
}

/**
 * Displays a success response.
 *
 * This function creates a new SuccessResponse object with the provided message
 * and status code, then outputs it directly to the response stream.
 *
 * @param string $message The success message to display to the user.
 * @param int $code HTTP status code for the response.
 * @return void
 * @see SuccessResponse
 * @author James Chen
 */
function success(string $message, int $code = 200): void
{
    echo new SuccessResponse($message, [], $code);
}

/**
 * Handles an error by displaying an error response and redirecting to an error
 * page.
 *
 * This function creates and echoes an `ErrorResponse` object with the provided
 * message and HTTP status code, then redirects the user to an error page.
 *
 * @param string $message The error message to display.
 * @param int $code The HTTP status code.
 * @return void
 * @see ErrorResponse
 * @author James Chen
 */
function error(string $message, int $code = 500): void
{
    echo new ErrorResponse($message, $code);
    redirect_to_error_page($message);
}

/**
 * Sends a JSON-formatted success response with a given message, data, and HTTP
 * status code.
 *
 * @param string $message The success message to include in the response.
 * @param array $data Additional data to include in the response payload.
 * @param int $code The HTTP status code to return (default is 200).
 * @return void
 */
function success_response(string $message, array $data = [], int $code = 200): void
{
    echo new SuccessResponse($message, $data, $code);
    exit(0);
}

/**
 * Sends a JSON-formatted error response with a given message, data, and HTTP
 * status code.
 *
 * @param string $message The error message to include in the response.
 * @param int $code The HTTP status code to return (default is 500).
 * @return void
 */
function error_response(string $message, int $code = 500): void
{
    echo new ErrorResponse($message, $code);
    exit(0);
}

/**
 * Retrieves and validates a required field from an array.
 *
 * Ensures the specified field exists and is not empty after trimming.
 * If the field is missing or empty, a JSON-formatted error response is sent.
 *
 * @param array $array The array to extract the field from.
 * @param string $field The name of the required field.
 * @return string The trimmed value of the required field.
 */
function require_field(array $array, string $field): string
{
    if (!isset($array[$field])) {
        error_response("Required field: $field");
    }

    $value = trim($array[$field]);
    if (empty($value)) {
        error_response("Field cannot be empty: $field");
    }

    return $value;
}
