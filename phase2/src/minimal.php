<?php

/**
 * This file serves as the core of the Minimal framework.
 *
 * @example
 *
 *     require_once 'minimal.php';
 *
 * @author James Chen
 */

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
 * Redirects the user to a different URL by sending a Location header.
 *
 * This function sets the `Location` header to a given URL. When the browser
 * receives the response, it will immediately redirect the user to that URL.
 *
 * @param string $url The destination URL path.
 * @param array $params The query parameters (or GET parameters).
 * @param string $prefix Optional prefix to prepend to the URL.
 * @return void
 * @author James Chen
 */
function redirect(string $url, array $params = [], string $prefix = ''): void
{
    $query_string = $params ? '?' . http_build_query($params, '', '&') : '';

    header("Location: " . $prefix . $url . $query_string);
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
    string $error_page = 'error.php'
): void {
    redirect($error_page, [
        'error_message' => $error_message,
    ]);
}

/**
 * Prints a success response.
 *
 * This function creates a new SuccessResponse object with the provided message
 * and status code, then outputs it directly to the response stream.
 *
 * @param string $message The success message to display to the user.
 * @param int $code HTTP status code for the response, defaults to 200 (OK).
 * @return void
 * @see SuccessResponse Related class that handles formatting the response.
 * @author James Chen
 */
function success(string $message, int $code = 200): void
{
    echo new SuccessResponse($message, $code);
}
