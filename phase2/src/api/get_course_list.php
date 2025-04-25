<?php

require_once "../minimal.php";

/**
 * Retrieves all courses for the current semester/year
 *
 * @api
 * @author Alexis Marx
 */
handle(HttpMethod::GET, function () {
    $departments = get_all_departments();
    success_response("Retrieved all departments.", [
        "list" => $departments
    ]);
});
