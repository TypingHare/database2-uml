<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

/**
 * Represents a successful response with a message and an HTTP status code.
 *
 * @author James Chen
 */
readonly class SuccessResponse
{
    /**
     * @param string $message The success message.
     * @param array $data data The response data.
     * @param int $code The HTTP status code.
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     */
    public function __construct(
        private string $message,
        private array  $data = [],
        private int    $code = 200,
    ) {
    }

    /**
     * Converts the response object to a JSON string.
     *
     * @return string The JSON-encoded success message.
     */
    public function __toString(): string
    {
        http_response_code($this->code);
        header('Content-Type: application/json');

        return json_encode([
            'status' => 'success',
            'url' => $_SERVER['REQUEST_URI'],
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
}

/**
 * Represents an error response with a message and an HTTP status code.
 *
 * @author James Chen
 */
readonly class ErrorResponse
{
    /**
     * @param string $message The error message.
     * @param array $data data The response data.
     * @param int $code The HTTP status code.
     * @see https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
     */
    public function __construct(
        private string $message,
        private array  $data = [],
        private int    $code = 400
    ) {
    }

    /**
     * Converts the response object to a JSON string.
     *
     * @return string The JSON-encoded error message.
     */
    public function __toString(): string
    {
        http_response_code($this->code);
        header('Content-Type: application/json');

        return json_encode([
            'status' => 'error',
            'url' => $_SERVER['REQUEST_URI'],
            'message' => $this->message,
            'data' => $this->data
        ]);
    }
}
