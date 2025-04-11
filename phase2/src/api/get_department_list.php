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
