<?php

require_once "../minimal.php";

/**
 * Retrieves all the departments.
 *
 * @api
 * @author James
 */
handle(HttpMethod::GET, function () {
    $departments = get_all_departments();
    success_response("Retrieved all departments.", [
        "list" => $departments
    ]);
});

error_response("HTTP method not supported: " . $_SERVER["REQUEST_METHOD"]);
